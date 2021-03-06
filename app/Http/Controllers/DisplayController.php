<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Pathfile;
use App\UserInput;
use App\Http\Controllers\HomeController;


class DisplayController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  { 
    $inputValues = $this->getInputValues();
    $obj = new HomeController;
    $frontEndValues = $obj->getDetails($inputValues[0] , $inputValues[1] , $inputValues[2] , $inputValues[3]);
    return view('/display' , compact('frontEndValues'));
  }


  /**
   *
   *
   * @return string
   */

  public function getInputValues()
  {
    $query = 'select * from pathfiles where user_id = '.\Auth::user()->id. ' order by created_at desc LIMIT 1';
    $filename = \DB::select($query);
    $inputValues = array($filename[0]->filepath, $filename[0]->dateformat, $filename[0]->phonemodel, $filename[0]->testtype);
    return $inputValues;
  }
  


  //Get content from file

  public function getContent(Request $request) 
  {
   $input = $request->all();
  //[personName] => Viswa HCM [fromDate] => 2014-06-04 [toDate] => 2017-07-11 )

    $fromArray = explode('-', $input['fromDate']);
     
    $fromyear = str_split($fromArray[0] , 2);
     
    $newfrom = $fromArray[1].'/'.$fromArray[2].'/'.$fromyear[1];

    //to date based on user date selection
    $toDate = date('m/d/y', strtotime($input['fromDate']. ' + ' . $input['days'] .'days'));

    $from = $newfrom;    //  whatsapp  mon/date/year  6/14/16
    $to = $toDate;
    
    $filePath = $this->getInputValues();
    try
    {
         
      $fileName = storage_path('app/').$filePath[0];

      $file = fopen($fileName , 'r');
      
      $array = array();

        $i = 0;

        while($line = fgets($file))
        {
          $array[$i] = explode(',', $line);   
          $i++;   
        }
        //echo $filePath[1]; exit();
        // changing the date formate to USA 
        if($filePath[1]==='UK')
        {
          foreach ($array as $key => $value) {
            $explode = explode('/' , $array[$key][0]);
            $array[$key][0]  = $explode[1].'/'.$explode[0].'/'.$explode[2];
          }
        }
        
        $selected_array = array();
        $textToDisplay = array();

        $j = 0;

        foreach ($array as $key => $value) {

                if(strtotime($array[$key][0]) >= strtotime($from) &&  strtotime($array[$key][0]) <= strtotime($to)) {
                    if(stripos($input['personName'], '*and*') === false){
                      $per = $input['personName'];
                       if(stripos($array[$key][1] , $per)) {
                         $textToDisplay[$j] = $array[$key];
                          $no_date = explode(':' ,$array[$key][1]);
                          $selected_array[$j] = preg_replace('/[^a-z]/i', ' ', end($no_date)).".";
                          $j++;
                       }    
                    } else {
                       $textToDisplay[$j] = $array[$key];
                       $no_date = explode(':' ,$array[$key][1]);
                       $selected_array[$j] = preg_replace('/[^a-z]/i', ' ', end($no_date));
                        $j++;               
                    }
                }
            }         
        if(count($textToDisplay) == 0){
          return back()->withInput();
        }
        $final_txt = array();
        foreach ($textToDisplay as $key => $value) {
          $final_txt[date('m/d/y', strtotime($textToDisplay[$key][0]))][] = $selected_array[$key];
        }
        $stringCon = json_encode($final_txt);
         
        if($stringCon !== '')
        {
          Storage::put('finaltext/'.\Auth::user()->id.'.txt', $stringCon);
        }            
        fclose($file);

        //save User Input values to database for later to use for api call and history
      UserInput::create(['name'=>$input['personName'] , 'fromdate'=>$from , 'todate'=>$to , 'noofdays' =>$input['days'] , 'user_id' => \Auth::user()->id]);
        return view('/texts' , compact('textToDisplay'));
    }
    catch (Illuminate\Filesystem\FileNotFoundException $exception)
    {
      return die("The file doesn't exist");
    }
  }
  
  // Get analysis based on the type of test

  public function getAnalysis() 
  {        
     // find the test type from the database 
    $query = 'select testtype from pathfiles where user_id = '.\Auth::user()->id. ' order by created_at desc LIMIT 1';
    $queryValues = \DB::select($query); 

    // Get the user input values from the database 
    $query1 = 'select * from user_inputs where user_id = '.\Auth::user()->id. ' order by created_at desc LIMIT 1';
    $userInputValues = \DB::select($query1); 
    $userInputs = json_decode(json_encode($userInputValues[0]), true);    
    if($queryValues[0]->testtype === 'single')
    {
      $responses = $this->getsingleAnalysis();
      $positive = array() ; $negative = array() ; $neutral = array(); $label = array();
       
       foreach ($responses as $key => $value) {
          $positive[$key] = $responses[$key]['probability']['pos'];
          $negative[$key] = $responses[$key]['probability']['neg'];
          $neutral[$key] = $responses[$key]['probability']['neutral'];
          $label[$key] = $responses[$key]['label'];        
        }
      return view('/singlegraph' , compact('positive' , 'negative' , 'neutral', 'label', 'userInputs' ));
    }
  }    

  public function getsingleAnalysis()
  {
    try 
    {
      // Get file content
      $fileName = storage_path('app/finaltext/'.\Auth::user()->id.'.txt');
      $file = file_get_contents($fileName);

      $textArray = json_decode($file , true);

      $responses = array();
      foreach ($textArray as $key => $value) {
        
        $text = json_encode($textArray[$key]);
        
        $textToSend = preg_replace('/[^a-z]/i', ' ', $text.".");

        // $ curl -d "text=great" http://text-processing.com/api/sentiment/
        
        $url = 'http://text-processing.com/api/sentiment/';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POSTFIELDS, 'text='.$textToSend);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       //execute post         
        $json = (curl_exec($ch));
        $jsontoArray = json_decode($json , true);
        $responses[$key] = $jsontoArray;
        curl_close($ch);
      }
      return $responses;        
    } catch (Illuminate\Filesystem\FileNotFoundException $e) {

      return back()->withInput();        
    }     
  }
}
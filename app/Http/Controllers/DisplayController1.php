<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Pathfile;
use App\UserInput;
use App\Http\Controllers\HomeController;


class DisplayController1 extends Controller
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
    $frontEndValues[0] = $obj->getDetails($inputValues[0][0] , $inputValues[0][1] , $inputValues[0][2]);
    $frontEndValues[1] = $obj->getDetails($inputValues[1][0] , $inputValues[1][1] , $inputValues[1][2]);
    return view('/display1' , compact('frontEndValues'));
  }


  /**
   *
   *
   * @return string
   */

  public function getInputValues()
  {
    $query = 'select * from pathfiles where user_id = '.\Auth::user()->id. ' order by created_at desc LIMIT 2';
    $filename = \DB::select($query);

    $inputValues = array();
     
    $inputValues[0] = array($filename[0]->filepath, $filename[0]->dateformat, $filename[0]->phonemodel, $filename[0]->testtype);
    $inputValues[1] = array($filename[1]->filepath, $filename[1]->dateformat, $filename[1]->phonemodel, $filename[1]->testtype);        
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
         
      $fileName = storage_path('app/').$filePath[0][0];

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
                    if(stripos($input['personName'][0], '*and*') === false){
                      $per = $input['personName'][0];
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
          echo "No Texts found in the following dates from Person".$input['personName'][0];
          //return view('display1' , compact('error1'));
          return back()->withInput();
        }
        $final_txt = array();
        foreach ($textToDisplay as $key => $value) {
          $final_txt[date('m/d/y', strtotime($textToDisplay[$key][0]))][] = $selected_array[$key];
        }
        $stringCon = json_encode($final_txt);
         
        if($stringCon !== '')
        {
          Storage::put('finaltext/'.\Auth::user()->id.'1.txt', $stringCon);
        }
         
        $textToDisplayOnUi = array();
        $textToDisplayOnUi[0] = $textToDisplay;
        fclose($file);

        //save User Input values to database for later to use for api call and history
      UserInput::create(['name'=>$input['personName'][0] , 'fromdate'=>$from , 'todate'=>$to , 'noofdays' =>$input['days'] , 'user_id' => \Auth::user()->id]);

      // second person
      unset($textToDisplay);
      $textToDisplay = array();

      $fileName = storage_path('app/').$filePath[1][0];

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
                    if(stripos($input['personName'][1], '*and*') === false){
                      $per = $input['personName'][1];
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

          echo "No Texts found in the following dates from Person".$input['personName'][1];
          //return view('display1' , compact('error2'));
          return back()->withInput();
        }
        $final_txt = array();
        foreach ($textToDisplay as $key => $value) {
          $final_txt[date('m/d/y', strtotime($textToDisplay[$key][0]))][] = $selected_array[$key];
        }
        $stringCon = '';
        $stringCon = json_encode($final_txt);
         
        if($stringCon !== '')
        {
          Storage::put('finaltext/'.\Auth::user()->id.'2.txt', $stringCon);
        }
         $textToDisplayOnUi[1] = $textToDisplay;         
        fclose($file);

        //save User Input values to database for later to use for api call and history
      UserInput::create(['name'=>$input['personName'][1] , 'fromdate'=>$from , 'todate'=>$to , 'noofdays' =>$input['days'] , 'user_id' => \Auth::user()->id]);

        return view('/twotexts' , compact('textToDisplayOnUi'));
    }
    catch (Illuminate\Filesystem\FileNotFoundException $exception)
    {
      return die("The file doesn't exist");
    }
  }
  
  // Get analysis based on the type of test

  public function getAnalysis() 
  {  
    
    // Get the user input values from the database 
    $query = 'select * from user_inputs where user_id = '.\Auth::user()->id. ' order by created_at desc LIMIT 2';
    $userInputValues = \DB::select($query);
     
    $userInputs1 = json_decode(json_encode($userInputValues[1]), true); 
     
    $userInputs2  = json_decode(json_encode($userInputValues[0]), true);
    
    
    $responses1 = $this->getdoubleAnalysis1();
    $positive1 = array() ; $negative1 = array() ; $neutral1 = array(); $label1 = array();    
   foreach ($responses1 as $key => $value) {
      $positive1[$key] = $responses1[$key]['probability']['pos'];
      $negative1[$key] = $responses1[$key]['probability']['neg'];
      $neutral1[$key] = $responses1[$key]['probability']['neutral'];
      $label1[$key] = $responses1[$key]['label'];        
    }
    $responses2 = $this->getdoubleAnalysis2();
    $positive2 = array() ; $negative2 = array() ; $neutral2 = array(); $label2 = array();
       
     foreach ($responses2 as $key => $value) {
        $positive2[$key] = $responses2[$key]['probability']['pos'];
        $negative2[$key] = $responses2[$key]['probability']['neg'];
        $neutral2[$key] = $responses2[$key]['probability']['neutral'];
        $label2[$key] = $responses2[$key]['label'];        
      }
    return view('/doublegraph' , compact('positive1' , 'negative1' , 'neutral1', 'label1', 'positive2' , 'negative2' , 'neutral2', 'label2', 'userInputs1' , 'userInputs2' ));    
  }    

  public function getdoubleAnalysis1()
  {
    try 
    {
      // Get file content
      $fileName = storage_path('app/finaltext/'.\Auth::user()->id.'1.txt');
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

      //return $result;        
    } catch (Illuminate\Filesystem\FileNotFoundException $e) {

      return back()->withInput();        
    }     
  }

  public function getdoubleAnalysis2()
  {
    try 
    {
      // Get file content
      $fileName = storage_path('app/finaltext/'.\Auth::user()->id.'2.txt');
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

      //return $result;        
    } catch (Illuminate\Filesystem\FileNotFoundException $e) {

      return back()->withInput();        
    }     
  }
}
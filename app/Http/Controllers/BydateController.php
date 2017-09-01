<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Pathfile;
use App\Http\Controllers\HomeController;;
use App\Date;
use App\UserInput;
use \Datetime;

class BydateController extends Controller
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
    return view('bydate');
  }

  //Upload Single text file
 /**
  *
  *@param array bydateStore(Request $request)
  *@return \Illuminate\Http\Response
 */

  public function bydateStore(Request $request){

    $datetype = $request['datetype'];
    $phoneModel = $request['phonemodel'];     

    $name = $request->file('chatfile')->getClientOriginalName();
    $ext = $request->file('chatfile')->getClientOriginalExtension();
    if($ext == 'txt'){        
      $path = $request->file('chatfile')->storeAs('chatfiles', time().'_'.\Auth::user()->name.$name);

      $content = file_get_contents($request->file('chatfile'));
      \Storage::disk('ftp')->put(time().'_'.\Auth::user()->name.$name, $content );
      

      if($phoneModel === 'windows')
      {
        $errors = array('Windows Phone is not yet supported');
        return view('bydate' , compact('errors')); 
      }
      $obj = new HomeController;
      $frontEndValues = $obj->getDetails($path , $datetype , $phoneModel);

      if(is_array($frontEndValues)) 
      { 
        // save it to database if everything goes well
        Pathfile::create(['filepath'=>$path , 'dateformat'=>$datetype  , 'phonemodel' =>$phoneModel , 'testtype' =>'bydate' ,'user_id' => \Auth::user()->id]);  
        return redirect('/display2');         
      }
      else 
      {
        $errors = array($frontEndValues);
        return view('bydate' , compact('errors'));
      }
    }
    else {
      $errors = array('Only Text files are Allowed');      
      return view('bydate' , compact('errors')); 
    }      
  }
  
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function display2()
  { 
    $inputValues = $this->getInputValues();
    $obj = new HomeController;
    $frontEndValues = $obj->getDetails($inputValues[0] , $inputValues[1] , $inputValues[2] , $inputValues[3]);
    return view('/display2' , compact('frontEndValues'));
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

    $toArray = explode('-', $input['toDate']);
     
    $toyear = str_split($toArray[0] , 2);
     
    $newto = $toArray[1].'/'.$toArray[2].'/'.$toyear[1];
    

    $from = $newfrom;    //  whatsapp  mon/date/year  6/14/16
    $to = $newto;

    $date1 = new DateTime($input['toDate']);

    $date2 = new DateTime($input['fromDate']);


    $days= date_diff( $date1 , $date2); 
    $input['days']  = $days->format('%a total days'); 
    
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
         $final_txt = '';
        foreach ($selected_array as $key => $value) {
          $final_txt  .=  $selected_array[$key]."<br />";
        }
        if($final_txt !== '')
        {
          Storage::put('finaltext/'.\Auth::user()->id.'.txt', $final_txt);
        }            
        fclose($file);
        if(count($textToDisplay) == 0){
          return back()->withInput();
        }
            

        //save User Input values to database for later to use for api call and history
      UserInput::create(['name'=>$input['personName'] , 'fromdate'=>$from , 'todate'=>$to , 'noofdays' =>$input['days'] , 'user_id' => \Auth::user()->id]);
        return view('/bydatetexts' , compact('textToDisplay'));
    }
    catch (Illuminate\Filesystem\FileNotFoundException $exception)
    {
      return die("The file doesn't exist");
    }
  }

  public function getAnalysis()
    {
      try
      {
        // Get the user input values from the database 
        $query1 = 'select * from user_inputs where user_id = '.\Auth::user()->id. ' order by created_at desc LIMIT 1';
        $userInputValues = \DB::select($query1); 
        $userInputs = json_decode(json_encode($userInputValues[0]), true);


        $fileName = storage_path('app/finaltext/'.\Auth::user()->id.'.txt');
        $textToSend = file_get_contents($fileName);


        // $ curl -d "text=great" http://text-processing.com/api/sentiment/
        
        $url = 'http://text-processing.com/api/sentiment/';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POSTFIELDS, 'text='.$textToSend);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       //execute post
        if(curl_exec($ch))
          {
            $json = (curl_exec($ch));
            $results = json_decode($json, true);       
            $probability = $results['probability'];
            $label = $results['label'];
            return view('/bydategraph' , compact('label' , 'probability' , 'userInputs'));
          }
        else {
          return ('No server Response');
        }
        curl_close($ch);
        

        //return $result;        
      } catch (Illuminate\Filesystem\FileNotFoundException $e) {

        return back()->withInput();        
      }      
    }

}

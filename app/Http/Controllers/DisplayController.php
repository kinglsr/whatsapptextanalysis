<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Pathfile;
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
      $path = $this->getPath();
      $obj = new HomeController;
      $frontEndValues = $obj->getDetails($path);
      return view('/display' , compact('frontEndValues'));
    }


    /**
     *
     *
     * @return string
     */

    public function getPath()
    {

      $query = 'select filepath from pathfiles where user_id = '.\Auth::user()->id. ' order by created_at desc LIMIT 1';
      $filename = \DB::select($query);
      return $filename[0]->filepath;
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
        
        $filePath = $this->getPath();

        try
        {
           
          $fileName = storage_path('app/').$filePath;

          $file = fopen($fileName , 'r');
          
          $array = array();

            $i = 0;


            while($line = fgets($file))
            {
                $array[$i] = explode(',', $line);   
                $i++;   
            }

            $selected_array = array();
            $textToDisplay = array();

            $j = 0;

            foreach ($array as $key => $value) {

                    if(strtotime($array[$key][0]) >= strtotime($from) &&  strtotime($array[$key][0]) <= strtotime($to)) {
                        if($input['personName'] !== 'both'){
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
            return view('/texts' , compact('textToDisplay'));
        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
          return die("The file doesn't exist");
        }
    }

    public function getAnalysis()
    {
      try {
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
            return view('\graph' , compact('label' , 'probability'));
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

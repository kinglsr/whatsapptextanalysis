<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Pathfile;

class HomeController extends Controller
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
        return view('home');
    }

    //Upload text file
   /**

    

   */

    public function store(Request $request){

      $name = $request->file('chatfile')->getClientOriginalName();
      $ext = $request->file('chatfile')->getClientOriginalExtension();
      if($ext == 'txt'){        
        $path = $request->file('chatfile')->storeAs('chatfiles', time().'_'.\Auth::user()->name.$name);

        Pathfile::create(['filepath'=>$path , 'user_id' => \Auth::user()->id]);

        $frontEndValues = $this->getDetails($path);

        if(is_array($frontEndValues)) 
        {      
          return redirect('/display');
        }
        else 
        {
          $errors = array($frontEndValues);
          return view('/home' , compact('errors'));
        }
      }
      else {
        $errors = array('Only Text files are Allowed');      
        return view('/home' , compact('errors')); 
      }      
    }

    public function getDetails($pathValue)
    { 
      $error = '';
      $fileName = storage_path('app/').$pathValue;
      $file = fopen($fileName , 'r');
        
        $array = array();

          $i = 0;


          while($line = fgets($file))
          {
              $array[$i] = $line;   
              $i++;   
          }
          if(count($array) == 0)
          {  
             $error = 'Given Text File is Empty, Error 1' ;
             return $error;
          }


          // validating the text file 
          $firstLine = reset($array);
          if($firstLine === '')
          {
            $error = 'Given Text File is Not from WhatsApp , Error 2';
            return $error;
          }

          $explode1 = explode(',', $firstLine);
          $startDate = $explode1[0];            
          if(stripos($startDate, '/'))
          {
            $eDate = explode('/', $startDate);              
          } else
          {
            $error = 'Given Text File is Not from WhatsApp , Error 3';
            return $error;
          }

          if(!checkdate($eDate[0], $eDate[1], $eDate[2]))
          {
            $error = 'Given Text File is Not from WhatsApp , Error 4';
            return $error;
          }


        $lastLine = end($array);
        $explode2 = explode(',', $lastLine);
        $endDate = $explode2[0];


        //select the few lines of the chat

        $exploded_array = array();
        if(count($array) >= 30)
        {
          for($y=0;$y<=30;$y++)
          {
            $exploded_array[$y] = explode(':', $array[$y]);
          }
        } else 
        {
          for($y=0;$y<=count($array);$y++)
          {
            $exploded_array[$y] = explode(':', $array[$y]);
          }
        }
      $names = array();  
      foreach ($exploded_array as $key => $value) {

        // for Iphone  '6/4/16, 11:11:54 AM: Viswa HCM: Hii Vinod'
         if(count($exploded_array[$key]) == 5)
          {
            end($exploded_array[$key]);
            $nameValue = prev($exploded_array[$key]);
            $names[$key] =  $nameValue;
          }

        // for Samsung  2/2/17, 14:08 - 804: No I need to buy books man
        if(count($exploded_array[$key]) == 3) {
          end($exploded_array[$key]);
          $nameValue = prev($exploded_array[$key]);
          if(stripos($nameValue, '-'))
          {
             // delete '-'
            $n = explode('-', $nameValue);
            $names[$key] = $n[1];
          } else 
          {
            $names[$key] =  $nameValue;
          }          
        }
      }

      $namesArray = array_unique($names);
      $reindex = array_values($namesArray);
      $person1 = $reindex[0];
      $person2 = $reindex[1];       

      $selectionValues = array($person1 , $person2 , $startDate , $endDate ) ;
      return ($selectionValues);        
    }   

}

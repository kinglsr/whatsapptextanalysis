<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Pathfile;
use App\Http\Controllers\DisplayController;;
use App\Date;

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

  //Upload Single text file
 /**
  *
  *@param array singleStore(Request $request)
  *@return \Illuminate\Http\Response
 */

  public function singleStore(Request $request){

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
        return view('singleperson' , compact('errors')); 
      }

      $frontEndValues = $this->getDetails($path , $datetype , $phoneModel);

      if(is_array($frontEndValues)) 
      { 
        // save it to database if everything goes well
        Pathfile::create(['filepath'=>$path , 'dateformat'=>$datetype  , 'phonemodel' =>$phoneModel , 'testtype' =>'single' ,'user_id' => \Auth::user()->id]);  
        return redirect('/display');         
      }
      else 
      {
        $errors = array($frontEndValues);
        return view('singleperson' , compact('errors'));
      }
    }
    else {
      $errors = array('Only Text files are Allowed');      
      return view('singleperson' , compact('errors')); 
    }      
  }

  public function getDetails($pathValue , $datetype , $phoneModel)
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
      if($datetype ==='USA'){
        // if USA Month day year           
        $firstLine = reset($array);
        if($firstLine === '')
        {
          $error = 'Given Text File is Not from WhatsApp or Empty Text File, Error 2';
          return $error;
        }
        if(stripos($firstLine, '/'))
        {
          $explode = Date::find_date_usa($firstLine);
          $startDate = $explode['month'].'/'.$explode['day'].'/'.$explode['year'];
          if($explode['month']=='' || $explode['day'] == '' || $explode['year']=='')
          {
            $error = 'Please verify the Date Format';
            return $error;
          }
          $lastLine = end($array);
          $explode1 = Date::find_date_usa($lastLine);
          $endDate = $explode1['month'].'/'.$explode1['day'].'/'.$explode1['year'];
        } else
        {
          $error = 'Given Text File is Not from WhatsApp , Error 3';
          return $error;
        }

        if(!checkdate($explode['month'], $explode['day'], $explode['year']))
        {
          $error = 'Given Text File is Not from WhatsApp , Error 4';
          return $error;
        }          
      } elseif($datetype ==='UK') {
        // if uk India 
        $firstLine = reset($array);
        if($firstLine === '')
        {
          $error = 'Given Text File is Not from WhatsApp , Error 2';
          return $error;
        }

        if(stripos($firstLine, '/'))
        {
          $explode = Date::find_date_uk($firstLine);
          $startDate = $explode['month'].'/'.$explode['day'].'/'.$explode['year'];
          if($explode['month']=='' || $explode['day'] == '' || $explode['year']=='')
          {
            $error = 'Please verify the Date Format';
            return $error;
          }
          $lastLine = end($array);
          $explode1 = Date::find_date_uk($lastLine);
          $endDate = $explode1['month'].'/'.$explode1['day'].'/'.$explode1['year'];
        } else
        {
          $error = 'Given Text File is Not from WhatsApp , Error 3';
          return $error;
        }

        if(!checkdate($explode['month'], $explode['day'], $explode['year']))
        {
          $error = 'Given Text File is Not from WhatsApp , Error 4';
          return $error;
        }          
      } else {
        $error = 'Given Date Formate is Not supported , Error 5';
        return $error;
      }

      //print_r($array); exit();

      //select the few lines of the chat

      $exploded_array = array();
      if(count($array) >= 30)
      {
        for($y=10;$y<30;$y++)
        {
          $exploded_array[$y] = explode(':', $array[$y]);
        }
      } else 
      {
        for($y=4;$y<count($array);$y++)
        { 
          $exploded_array[$y] = explode(':', $array[$y]);
        }
      }
    $names = array();  
    foreach ($exploded_array as $key => $value) {

      // for Iphone  '6/24/16, 11:11:54 AM: Viswa HCM: Hii Vinod'
      // for windows  6/24/2017 11:06:55 PM: Midhun: https://youtu.be/dUhMQYW-xmk
      //6/24/2017 11:08:25 PM: Sai: https://youtu.be/dUhMQYW-xmk


       if(count($exploded_array[$key]) >= 5 || $phoneModel === 'iphone' || $phoneModel === 'windows')
        {
          end($exploded_array[$key]);
          $nameValue = prev($exploded_array[$key]);
          $names[$key] =  $nameValue;
        }

      // for Samsung  2/2/17, 14:08 - 804: No I need to buy books man
        //            12/08/17, 9:04 AM - Sai Pavan: <Media omitted>

      if(count($exploded_array[$key]) < 5 || $phoneModel === 'android') {
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
    if(count($reindex) > 2)
    {
      $person1 = $reindex[1];
      $person2 = $reindex[2];
    }
    $person1 = $reindex[0];
    $person2 = $reindex[1];

    $selectionValues = array($person1 , $person2 , $startDate , $endDate) ;
    return ($selectionValues);        
  }

  //Upload double text file
 /**
  *
  *@param array doubleleStore(Request $request)
  *@return \Illuminate\Http\Response
 */

  public function doubleStore(Request $request){

    $datetype = $request['datetype'];
    $phoneModel = $request['phonemodel']; 
    $fileName1 = $request->file('chatfile.0')->getClientOriginalName();
    $fileName2 = $request->file('chatfile.1')->getClientOriginalName();
    $ext1 = $request->file('chatfile.0')->getClientOriginalExtension();
    $ext2 = $request->file('chatfile.1')->getClientOriginalExtension();

    //echo $fileName1; echo $fileName2; exit();

    if( $ext1 !== 'txt' || $ext2 !== 'txt') 
    {
      $errors = 'The format is not supported';
      return view('twopersons' , compact('errors'));
    }

    $path1 = $request->file('chatfile.0')->storeAs('chatfiles', time().'_'.\Auth::user()->name.$fileName1);

      $content1 = file_get_contents($request->file('chatfile.0'));
      \Storage::disk('ftp')->put(time().'_'.\Auth::user()->name.$fileName1, $content );

    $path2 = $request->file('chatfile.1')->storeAs('chatfiles', time().'_'.\Auth::user()->name.$fileName2);

      $content2 = file_get_contents($request->file('chatfile.1'));
      \Storage::disk('ftp')->put(time().'_'.\Auth::user()->name.$fileName1, $content2 );
      

      if($phoneModel === 'windows')
      {
        $errors = array('Windows Phone is not yet supported');
        return view('twopersons' , compact('errors')); 
      }

      $frontEndValues1 = $this->getDetails($path1 , $datetype , $phoneModel);
      $frontEndValues2 = $this->getDetails($path2 , $datetype , $phoneModel);

    if(is_array($frontEndValues1) && is_array($frontEndValues2)) 
    { 
      // save it to database if everything goes well
      Pathfile::create(['filepath'=>$path1 , 'dateformat'=>$datetype  , 'phonemodel' =>$phoneModel , 'testtype' =>'double' ,'user_id' => \Auth::user()->id]); 
      // save it to database if everything goes well
      Pathfile::create(['filepath'=>$path2 , 'dateformat'=>$datetype  , 'phonemodel' =>$phoneModel , 'testtype' =>'double' ,'user_id' => \Auth::user()->id]); 
      return redirect('/display1');         
    }
    else 
    {
      if(!is_array($frontEndValues1))
      {
        $errors = array($frontEndValues1);
      } else {
        $errors = array($frontEndValues2);
      }        
      return view('twopersons' , compact('errors'));
    }
  } 

}

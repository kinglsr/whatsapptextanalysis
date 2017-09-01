<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;



class SampleController extends Controller
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

    return view('sample');
  }

  /**
   * Show the application dashboard.
   * @param Request $request
   * @return \Illuminate\Http\Response
   */
  public function post(Request $request)
  {

    $values = $request->all();

    if(isset($values['text']) && $values['language']){

      $text = preg_replace('/[^a-z]/i', ' ', $values['text']);

      $post =array("language" => $values['language'] , 'text'=> $text );

      

      // $ curl -d "text=great" http://text-processing.com/api/sentiment/
      
      $url = 'http://text-processing.com/api/sentiment/';

      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL, $url);
      curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($post));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      //execute post         
      $json = (curl_exec($ch));
      $results = json_decode($json, true);       
      $probability = $results['probability'];
      $label = $results['label'];
      return view('/samplegraph' , compact('label' , 'probability'));      
    }
    
     else {
       return back()->withInput();
     }
  }


}
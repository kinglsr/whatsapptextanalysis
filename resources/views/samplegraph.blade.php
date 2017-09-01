@extends('layouts.app')


@section('content')
    <div class='container'>
       <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <a href="/sample"> Make Another Sample Test</a>
          </div>
      </div>
    </div>
    <div class="container">
    Showing the Text Results
    </div>
    <div class="container">              
      <div class="thumbnail">
        @if(json_encode($label) =='"pos"')     
         <div class="alert alert-success" role="alert">    
          <h5>  The Text is : {{json_encode($label)}}. Hurray, your Text is awesome. Keep Going.<i class="em em-blush"></i></h5>
        </div>
        @endif
        @if(json_encode($label) =='"neutral"')     
         <div class="alert alert-warning" role="alert">   
          <h5>  The Text is : {{json_encode($label)}}. Well, Nothing to tell <i class="em em-baby_chick"> </i> </h5>
        </div>
        @endif
        @if(json_encode($label) =='"neg"')     
        <div class="alert alert-danger" role="alert">   
          <h5>  The Text is : {{json_encode($label)}}. It's time to work on your language <i class="em em-cry"></i> </h5>
        </div>
        @endif 
        <ol> 
            @foreach($probability as $key => $value)       
              <li> {{$key}} : {{round($value , 2) }}</li>        
            @endforeach
        </ol>       
      </div>      
    </div> 
    <div class="container">
      <div id='app'>
       <graph :labels="['Positive' , 'Neutral' , 'Negative']" :values="{{json_encode($probability)}}">
       </graph>
      </div>
    </div>

      
@endsection
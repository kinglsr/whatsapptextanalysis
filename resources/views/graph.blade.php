@extends('layouts.app')


@section('content')
    <div class='container'>
       <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <a href="/display"> Make Another New Test</a>
          </div>
      </div>
    </div> 
    @if(json_encode($label) =='"pos"')     
    <div class="alert alert-success" role="alert">    
        <h5>  The Chat is : {{json_encode($label)}}. Hurray, your relaionship is awesome. Keep Going. </h5>
      </div>
      @endif
      @if(json_encode($label) =='"neutral"')     
    <div class="alert alert-primary" role="alert">   
        <h5>  The Chat is : {{json_encode($label)}}. Well, Nothing to tell </h5>
      </div>
      @endif
      @if(json_encode($label) =='"neg"')     
    <div class="alert alert-danger" role="alert">   
        <h5>  The Chat is : {{json_encode($label)}}. It's time to work on your relationship Nothing is more important than your health </h5>
      </div>
      @endif
<div class="page-header">
 <div id='app'>
   <graph :labels="['Positive' , 'Neutral' , 'Negative']" :values="{{json_encode($probability)}}">
   </graph>
</div>
</div>
@endsection
@extends('layouts.app')


@section('content')
    <div class='container'>
       <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <a href="/display"> Make Another New Test</a>
          </div>
      </div>
    </div>
    <div class="container">
    Showing the Text Results
    </div>
    <div class="container">
      <div class="thumbnail">
      @foreach($label as $key => $value)
       <ul> 
         <li>The chat with {{$userInputs['name']}} on {{$key}} is: {{$value }}</li>
        </ul>
      @endforeach
      </div>
      <div class='container'>
      <div id='app'>
         <graph1 :dates="{{json_encode(array_keys($label))}}" :positive="{{json_encode(array_values($positive))}}" :negative="{{json_encode(array_values($negative))}}"
         :neutral="{{json_encode(array_values($neutral))}}" :person="{{json_encode($userInputs)}}" >
         </graph1>
      </div>
    </div>
@endsection
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
      <div class="row">
        <div class="col-sm-6 col-md-4">
          <div class="thumbnail">
            @foreach($label1 as $key => $value)
             <ul> 
               <li>The chat with {{$userInputs2['name']}} on {{$key}} is: {{$value }}
               </li>
              </ul>
            @endforeach
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
          <div class="thumbnail">
            @foreach($label2 as $key => $value)
             <ul> 
               <li>The chat with {{$userInputs1['name']}} on {{$key}} is: {{$value }}
               </li>
              </ul>
            @endforeach
        </div>
      </div>      
      <div id='app'>
         <graphdouble :dates="{{json_encode(array_keys($label1))}}" :person1="{{json_encode($userInputs2)}}"  :person2="{{json_encode($userInputs1)}}" :positive1="{{json_encode(array_values($positive1))}}" :positive2="{{json_encode(array_values($positive2))}}"  :negative1="{{json_encode(array_values($negative1))}}" :negative2="{{json_encode(array_values($negative2))}}"  :neutral1="{{json_encode(array_values($neutral1))}}" :neutral2="{{json_encode(array_values($neutral2))}}">
         </graphdouble>
      </div>
    </div>
@endsection

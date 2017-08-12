@extends('layouts.app')


@section('content')

	<div class="panel-heading"> 
	  <a>  Sentiment: {{json_encode($label)}} </a>
	</div>

<div class="page-header">
 <div id='app'>
   <graph :labels="['Positive' , 'Neutral' , 'Negative']" :values="{{json_encode($probability)}}">
   </graph>
</div>
</div>
@endsection
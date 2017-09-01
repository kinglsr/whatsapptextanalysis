@extends('layouts.app')


@section('content')
 <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Hurray!!</div>                       
                <div class="panel-body">                    
                    Select the Dates and Name you want to search
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-offset-2">
             <form action="/display2/getContent" method="POST" >
             {{ csrf_field() }}
              <div class="form-group">
                <label for="personName">Select list:</label>
                <select class="form-control" id="personName" name="personName" required>
                    <option value="{{$frontEndValues[0]}}">{{$frontEndValues[0]}}</option>
                    <option value="{{$frontEndValues[1]}}">{{$frontEndValues[1]}}</option>
                    <option value="both {{$frontEndValues[0][0]}} *and* {{$frontEndValues[0][1]}} ">Both</option>
                </select>
              </div>              
              <div class="form-group">
                <label for="fromDate">From Date</label>
                <input class="form-control" type="date" name="fromDate" id="fromDate" required>
              </div>
              @if(count($frontEndValues))
                 <div class="alert alert-success" role="alert">FROM DATE SHOULD BE LESS THAN To DATE </br> Select Date Between {{$frontEndValues[2]}} and {{$frontEndValues[3]}}
                 </div>            
               @endif
              <div class="form-group">
                <label for="toDate">From Date</label>
                <input class="form-control" type="date" name="toDate" id="toDate" required>
              </div>
              @if($frontEndValues)
                 <div class="alert alert-success" role="alert">TO DATE SHOULD BE GREATER THAN FROM DATE </br>
                 Select Date Between {{$frontEndValues[2]}} and {{@$frontEndValues[3]}}               
                 </div>            
               @endif      
              
              <div class="form-group">
              <button type="submit" class="btn btn-primary">Get the Content</button>
              </div>
            </form>
       </div>            
    </div>
</div>
@endsection

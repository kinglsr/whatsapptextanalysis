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
             <form action="/display/getContent" method="POST" >
             {{ csrf_field() }}
              <div class="form-group">
                <label for="personName">Select list:</label>
                <select class="form-control" id="personName" name="personName" required>
                    <option value="{{$frontEndValues[0]}}">{{$frontEndValues[0]}}</option>
                    <option value="{{$frontEndValues[1]}}">{{$frontEndValues[1]}}</option>
                    <option value="both {{$frontEndValues[0]}} *and* {{$frontEndValues[1]}} ">Both</option>
                </select>
              </div>              
              <div class="form-group">
              @if(count($frontEndValues))
                <label for="fromDate">Select Date Between {{$frontEndValues[2]}} & {{$frontEndValues[3]}}</label>
                <input class="form-control" type="date" name="fromDate" id="fromDate" required>
              </div>                   
               @endif
              <div class="form-group">
              <label for="days">Select No of days you want to text the data
               <select class="form-control"  id="days" name="days" value = '0' required>
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                </select>
              </div>              
              <div class="form-group">
              <button type="submit" class="btn btn-primary">Get the Content</button>
              </div>
            </form>
       </div>            
    </div>
</div>
@endsection

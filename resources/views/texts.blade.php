@extends('layouts.app')


@section('content')
 <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form action="/display/getAnalysis" method="GET">
            {{ csrf_field() }}
            <div class="form-group">                                            
                <button type="submit" class="btn btn-primary">Send texts to Lab</button>
              </div> 
              <div>
                <a href="/display">Change Dates</a>
              </div>             
            </form>            
        </div>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Displaying the Selected Texts!</h3></div                      
                <div class="panel-body">                  
                    <ul class="list-group">
                      @foreach ($textToDisplay as $key => $val)
                      <li class="list-group-item"> {{$textToDisplay[$key][0]. '  ' .$textToDisplay[$key][1]}} </li>
                      @endforeach
                    </ul>
                </div>
            </div>
        </div>                
    </div>
</div>
@endsection

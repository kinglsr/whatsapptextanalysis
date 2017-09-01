@extends('layouts.app')


@section('content')
 <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form action="/display1/getAnalysis" method="GET">
            {{ csrf_field() }}
            <div class="form-group">                                            
                <button type="submit" class="btn btn-primary">Send texts to Lab</button>
              </div> 
              <div>
                <a href="/display1">Change Dates</a>
              </div>             
            </form>            
        </div>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading "><h3>Displaying the Selected Texts Of Chat One!</h3></div>
                <div class="panel-body">                  
                    <ul class="list-group">
                      @for ($i = 0; $i < 1; $i++)
                       @foreach ($textToDisplayOnUi[$i] as $key)
                      <li class="list-group-item"> {{$key[0]. '  ' .$key[1]}} </li>
                      @endforeach
                      @endfor
                    </ul>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading "><h3>Displaying the Selected Texts Of Chat Two!</h3></div>
                <div class="panel-body">                  
                    <ul class="list-group">
                      @for ($i = 1; $i < 2; $i++)
                       @foreach ($textToDisplayOnUi[$i] as $key)
                      <li class="list-group-item"> {{$key[0]. '  ' .$key[1]}} </li>
                      @endforeach
                      @endfor
                    </ul>
                </div>
            </div>
          <div class="col-md-8 col-md-offset-2">
            <form action="/display1/getAnalysis" method="GET">
            {{ csrf_field() }}
            <div class="form-group">                                            
                <button type="submit" class="btn btn-primary">Send texts to Lab</button>
              </div> 
              <div>
                <a href="/display1">Change Dates</a>
              </div> 
            </form>            
        </div>  
        </div>                
    </div>
</div>
@endsection

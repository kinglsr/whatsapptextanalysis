@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome {{Auth::user()->name}}</div>         
                <div class="panel-body">
                  Enter Some sample text
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-offset-2">
            <form action="/sample/post" method="POST" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="form-group">
                 <label for="language"><h4>Select Language </h4></label>
                 <div class="form-check">
                  <label class="radio-inline active">
                    <input type="radio" name="language" id="english" value="english" checked="" required> English
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="language" id="dutch" value="dutch" required> Dutch
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="language" id="french" value="french" required> French
                  </label>
              </div>
              <div class="form-group">
                 <label for="text"><h4>Sample Text </h4></label>
                 <textarea class="form-control" rows="10" value="text" id="text" name="text" required></textarea>
                 Max Characters 50000
              </div>
              @if(count($errors))
             @foreach($errors as $error)
             <div class="alert alert-danger" role="alert">{{$error}}</div>
            @endforeach
            @endif
                <button type="submit" class="btn btn-primary btn-lg">Submit</button>
            </form>            
       </div>            
    </div>
</div>
@endsection

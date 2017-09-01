@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">    
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome {{Auth::user()->name}}</div>                     
                <div class="panel-body">
                    It's time to find the Real truth!
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-offset-2">
          <div class="panel panel-default">
            <div class="panel-body">
              <form action="/home/double" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                 <label for="chatfile"><h4>File input</h4></label>
                 <input type="file" id="chatfile[]" name="chatfile[]" class="form-control-file" required aria-describedby="fileHelp">
                 <small id="fileHelp" class="form-text text-muted">Upload the WhatsApp Chat Text File.
                </div>
                <div class="form-group">
                 <label for="chatfile"><h4>File input</h4></label>
                 <input type="file" id="chatfile[]" name="chatfile[]" class="form-control-file" required aria-describedby="fileHelp">
                 <small id="fileHelp" class="form-text text-muted">Upload the WhatsApp Chat Text File.
                </div>
                <div class="form-group">
                  <label><h4>Select Date Format</h4></label>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input" type="radio" name="datetype" id="datetype" value="USA" required >
                      Month/Date/Year , USA/CANADA
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input" type="radio" name="datetype" id="datetype" value="UK" required>
                      Date/Month/Year , UK INDIA Europe
                    </label>
                  </div>
                <div class="form-group">
                  <label><h4>Select Phone Model the chat file generated from:</h4></label>
                  <div class="form-check">
                    <label class="radio-inline">
                      <input type="radio" name="phonemodel" id="iphone" value="iphone" required> Iphone
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="phonemodel" id="android" value="android" required> Android
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="phonemodel" id="windows" value="windows" required> Windows
                    </label>
                  </div>
                </div>
                @if(count($errors))
                 <div class="alert alert-danger" role="alert">{{$errors}}</div>
                @endif                
            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
              </form>
            </div>
          </div>               
       </div>            
    </div>
</div>
@endsection

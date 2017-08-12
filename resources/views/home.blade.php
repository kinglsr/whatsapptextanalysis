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
            <form action="/home" method="POST" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="form-group">
                 <label for="chatfile">File input</label>
                 <input type="file" id="chatfile" name="chatfile" class="form-control-file" required aria-describedby="fileHelp">
                 <small id="fileHelp" class="form-text text-muted">Upload the WhatsApp Chat Text File.
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

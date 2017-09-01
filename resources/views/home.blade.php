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
    </div>
    <div class="row">
      <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
          <img src="..." alt="...">
          <div class="caption">
            <h3>Sample Text</h3>
            <p>Click Here and Paste any Sample data and Do the analysis</p>
            <p><a href="/sample" class="btn btn-default" role="button">Sample</a></p>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
          <img src="..." alt="...">
          <div class="caption">
            <h3>Single Person By Date</h3>
            <p>Select a single chat file and figureout the relationship From Start Date to End Date</p>
            <p><a href="/bydate" class="btn btn-default" role="button">ByDate</a></p>
          </div>
        </div>
      </div>  
      <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
          <img src="..." alt="...">
          <div class="caption">
            <h3>Single Person</h3>
            <p>Select a single chat file and figureout the relationship</p>
            <p><a href="/single" class="btn btn-default" role="button">Single</a></p>
          </div>
        </div>
      </div>     
    </div> 
    <div class="row">
      <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
          <img src="..." alt="...">
          <div class="caption">
            <h3>Two Persons</h3>
            <p>Select two chat files and do the tests against them and findout which one fits for you</p>
            <p> <a href="/double" class="btn btn-default" role="button">Double</a></p>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
          <img src="..." alt="...">
          <div class="caption">
            <h3>Group Chat </h3>
            <p>Do the analysis from the group chats <br/>.
            </p>
            <p><a href="/group" class="btn btn-default" role="button">Group</a></p>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
          <img src="..." alt="...">
          <div class="caption">
            <h3>History / Help Document</h3>
            <p>Select History for your previous test results or Help Document</p>
            <p><a href="/history" class="btn btn-primary" role="button">History</a>
               <a href="/help" class="btn btn-default" role="button">Help</a>
            </p>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

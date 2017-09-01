<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/home/single', 'HomeController@singleStore');

Route::post('/home/bydate', 'BydateController@bydateStore');

Route::post('/home/double', 'HomeController@doubleStore');

Route::post('/home/group', 'HomeController@groupStore');

Route::get('/sample', 'SampleController@index')->name('sample');
Route::post('/sample/post', 'SampleController@post');


Route::get('/bydate', 'BydateController@index')->name('sample');

Route::get('/display', 'DisplayController@index')->name('display');

Route::post('/display/getContent', 'DisplayController@getContent');

Route::get('/display/getAnalysis' , 'DisplayController@getAnalysis');

Route::get('/display1', 'DisplayController1@index')->name('display1');

Route::post('/display1/getContent', 'DisplayController1@getContent');

Route::get('/display1/getAnalysis' , 'DisplayController1@getAnalysis');

Route::get('/display2', 'BydateController@display2')->name('display2');

Route::post('/display2/getContent', 'BydateController@getContent');

Route::get('/display2/getAnalysis' , 'BydateController@getAnalysis');


Route::get('/single' , function(){
	return view('singleperson');
});

Route::get('/double' , function(){
	return view('twopersons');
});

Route::get('/group' , function(){
	return view('group');
});


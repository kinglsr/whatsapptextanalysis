<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInput extends Model
{
    //User Input to get the texts
    protected $fillable = ['name' ,'fromdate' , 'todate' ,'noofdays' ,'user_id'];

   
    //function 
    public function user() // $file->user->name
    {
    	return $this->belongsTo(User::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pathfile extends Model
{
    
   protected $fillable = ['filepath' ,'dateformat' , 'testtype' , 'phonemodel' ,'user_id'];

   
    //function to get the file uploaded by the user
    public function user() // $file->user->name
    {
    	return $this->belongsTo(User::class);
    }
}

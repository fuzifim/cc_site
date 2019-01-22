<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User_login_redirect extends Model
{
    protected $table = 'user_login_redirect';
	public $timestamps = false; 
	public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}

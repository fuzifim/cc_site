<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users_join_email extends Model
{
    protected $table = 'users_join_email'; 
	public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
	public function email(){
        return $this->hasOne('App\Model\Email', 'id', 'email_id');
    }
}

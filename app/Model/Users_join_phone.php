<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users_join_phone extends Model
{
    protected $table = 'users_join_phone'; 
	public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
	public function phone(){
        return $this->hasOne('App\Model\Phone', 'id', 'phone_id');
    }
}

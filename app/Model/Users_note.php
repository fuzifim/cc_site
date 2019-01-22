<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users_note extends Model
{
    protected $table = 'users_note'; 
	public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}

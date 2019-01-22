<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users_join extends Model
{
    protected $table = 'users_join'; 
	public function user(){
        return $this->hasOne('App\User', 'id', 'user_parent_id');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users_join_address extends Model
{
    protected $table = 'users_join_address'; 
	public function user(){
        return $this->hasOne('App\User', 'id', 'user_parent_id');
    }
	public function address(){
        return $this->hasOne('App\Model\Address', 'id', 'address_id');
    }
}

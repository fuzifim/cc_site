<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Oauth_identities extends Model
{
    protected $table = 'oauth_identities';
	public $timestamps = false; 
	public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}

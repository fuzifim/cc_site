<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Channel_role extends Model
{
    protected $table = 'channel_role';
    public $timestamps = false; 
	public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
	
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = 'phone';
	public $timestamps = false; 
	public function phoneJoinUser(){
        return $this->hasOne('App\Model\Phone_join', 'phone_parent_id', 'id')->where('phone_join_table','=','users');
    }
}

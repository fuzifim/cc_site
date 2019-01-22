<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table = 'email';
	public $timestamps = false; 
	public function emailJoinUser(){
        return $this->hasOne('App\Model\Email_join', 'email_parent_id', 'id')->where('email_join_table','=','users');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Email_join extends Model
{
    protected $table = 'email_join';
    public $timestamps = false;
	public function email(){
        return $this->hasOne('App\Model\Email', 'id', 'email_parent_id');
    }
	public function user(){
        return $this->hasOne('App\User', 'id', 'table_parent_id');
    }
}

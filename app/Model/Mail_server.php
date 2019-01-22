<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Mail_server extends Model
{
    protected $table = 'mail_server';
	public $timestamps = false; 
	public function serviceAttribute(){
        return $this->hasOne('App\Model\Services_attribute', 'id', 'service_attribute_id');
    }
	public function mail_serverJoinChannel(){
        return $this->hasOne('App\Model\Mail_server_join', 'mail_server_parent_id', 'id')->where('table','=','channel');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Mail_server_join extends Model
{
    protected $table = 'mail_server_join';
	public $timestamps = false; 
	public function mail_server(){
        return $this->hasOne('App\Model\Mail_server', 'id', 'mail_server_parent_id');
    }
	public function channel(){
        return $this->hasOne('App\Model\Channel', 'id', 'table_parent_id');
    }
}

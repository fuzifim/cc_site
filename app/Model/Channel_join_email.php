<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Channel_join_email extends Model
{
    protected $table = 'channel_join_email';
    public $timestamps = false; 
	public function email(){
        return $this->hasOne('App\Model\Email', 'id', 'email_id');
    }
}

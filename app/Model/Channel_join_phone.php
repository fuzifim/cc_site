<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Channel_join_phone extends Model
{
    protected $table = 'channel_join_phone';
    public $timestamps = false; 
	public function phone(){
        return $this->hasOne('App\Model\Phone', 'id', 'phone_id');
    }
}

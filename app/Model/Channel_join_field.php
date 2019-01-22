<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Channel_join_field extends Model
{
    protected $table = 'channel_join_field';
    public $timestamps = false; 
	public function field(){
        return $this->hasOne('App\Model\Fields', 'id', 'field_id');
    } 
}

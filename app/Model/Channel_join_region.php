<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Channel_join_region extends Model
{
    protected $table = 'channel_join_region';
    public $timestamps = false; 
	public function region(){
        return $this->hasOne('App\Model\Regions', 'id', 'region_id');
    }
}

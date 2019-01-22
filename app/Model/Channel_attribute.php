<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Channel_attribute extends Model
{
	//protected $connection = 'mysqlzcom';
    protected $table = 'channel_attribute';
	public $timestamps = false; 
	public function channel(){
        return $this->hasOne('App\Model\Channel', 'id', 'channel_parent_id')->where('channel_status','=','active');
    }
	public function theme(){
        return $this->hasOne('App\Model\Themes', 'id', 'channel_attribute_value')->where('status','=','active');
    }
	public function media(){
        return $this->hasOne('App\Model\Media', 'id', 'channel_attribute_value');
    }
}

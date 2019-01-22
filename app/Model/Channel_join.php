<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Channel_join extends Model
{
	//protected $connection = 'mysqlzcom';
    protected $table = 'channel_join';
    public $timestamps = false;
	public function field(){
        return $this->hasOne('App\Model\Fields', 'id', 'table_parent_id');
    } 
	public function service(){
        return $this->hasOne('App\Model\Services', 'id', 'table_parent_id');
    } 
}

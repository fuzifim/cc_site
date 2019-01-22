<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cloud extends Model
{
    protected $table = 'cloud';
	public $timestamps = false; 
	public function serviceAttribute(){
        return $this->hasOne('App\Model\Services_attribute', 'id', 'service_attribute_id');
    }
	public function cloudJoinChannel(){
        return $this->hasOne('App\Model\Cloud_join', 'cloud_parent_id', 'id')->where('table','=','channel');
    }
}

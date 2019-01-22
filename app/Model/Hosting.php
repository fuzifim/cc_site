<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Hosting extends Model
{
    protected $table = 'hosting';
	public $timestamps = false; 
	public function serviceAttribute(){
        return $this->hasOne('App\Model\Services_attribute', 'id', 'service_attribute_id');
    }
	public function hostingJoinChannel(){
        return $this->hasOne('App\Model\Hosting_join', 'hosting_parent_id', 'id')->where('table','=','channel');
    }
}

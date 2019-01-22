<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users_attribute extends Model
{
    protected $table = 'users_attribute';
    public $timestamps = false; 
	
	public function media(){
		return $this->hasOne('App\Model\Media', 'id', 'attribute_value');
	}
	public function region(){
		return $this->hasOne('App\Model\Regions', 'id', 'attribute_value');
	}
	public function subRegion(){
		return $this->hasOne('App\Model\Subregions', 'id', 'attribute_value');
	}
}

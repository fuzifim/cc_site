<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Region_ward extends Model
{
    protected $table = 'region_ward';
    public $timestamps = false;
    public function region_district(){
    	return $this->hasMany('App\Model\Region_district','id','region_district_id');
    }
}

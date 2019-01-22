<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class Address extends Model
{
    protected $table = 'address';
    public $timestamps = false; 
	public function joinRegion(){
        return $this->hasOne('App\Model\Address_join_region', 'address_id', 'id');
    }
	public function joinSubRegion(){
        return $this->hasOne('App\Model\Address_join_subregion', 'address_id', 'id');
    }
	public function joinDistrict(){
        return $this->hasOne('App\Model\Address_join_district', 'address_id', 'id');
    }
	public function joinWard(){
        return $this->hasOne('App\Model\Address_join_ward', 'address_id', 'id');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class Address_join_district extends Model
{
    protected $table = 'address_join_district';
    public $timestamps = false; 
	public function district(){
        return $this->hasOne('App\Model\Region_district', 'id', 'district_id');
    }
}

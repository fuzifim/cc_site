<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class Address_join_region extends Model
{
    protected $table = 'address_join_region';
    public $timestamps = false; 
	public function region(){
        return $this->hasOne('App\Model\Regions', 'id', 'region_id');
    }
}

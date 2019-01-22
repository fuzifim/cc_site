<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class Address_join_subregion extends Model
{
    protected $table = 'address_join_subregion';
    public $timestamps = false; 
	public function subRegion(){
        return $this->hasOne('App\Model\Subregions', 'id', 'subregion_id');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class Address_join_ward extends Model
{
    protected $table = 'address_join_ward';
    public $timestamps = false; 
	public function ward(){
        return $this->hasOne('App\Model\Region_ward', 'id', 'ward_id');
    }
	public function address(){
        return $this->hasOne('App\Model\Address', 'id', 'address_id');
    }
}

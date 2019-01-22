<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Services extends Model
{
    protected $table = 'services';
    public $timestamps = false; 
	public function joinDomain(){
        return $this->hasOne('App\Model\Services_join', 'services_parent_id', 'id')->where('join_table','=','domains');
    }
	public function attribute(){
        return $this->hasOne('App\Model\Services_attribute', 'parent_id', 'id');
    }
	public function attributeAll(){
        return $this->hasMany('App\Model\Services_attribute', 'parent_id', 'id')->where('status','=','active')->orderBy('order_by','asc');
    }
	public function attributeChannel(){
        return $this->hasOne('App\Model\Services_attribute', 'parent_id', 'id')->where('attribute_type','=','channel');
    }
}

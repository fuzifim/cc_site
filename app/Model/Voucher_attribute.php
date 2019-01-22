<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Voucher_attribute extends Model
{
    protected $table = 'voucher_attribute';
    public $timestamps = false; 
	public function voucher(){
        return $this->hasOne('App\Model\Voucher', 'id', 'parent_id');
    }
	public function voucherCode(){
        return $this->hasOne('App\Model\Voucher_attribute', 'id', 'parent_id')->where('attribute_type','=','code');
    }
	public function author(){
        return $this->hasOne('App\Model\Voucher_attribute', 'parent_id', 'id')->where('attribute_type','=','author');
    }
	public function user(){
        return $this->hasOne('App\User', 'id', 'attribute_value');
    }
}

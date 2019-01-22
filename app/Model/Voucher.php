<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    //
	protected $table = 'voucher';
    public $timestamps = false; 
	public function voucherAttributeCode(){
        return $this->hasOne('App\Model\Voucher_attribute', 'parent_id', 'id')->where('attribute_type','=','code');
    }
}

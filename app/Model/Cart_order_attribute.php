<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cart_order_attribute extends Model
{
    protected $table = 'cart_order_attribute';
    public $timestamps = false; 
	public function cart(){
        return $this->hasOne('App\Model\Cart_order', 'id', 'parent_id');
    }
	public function service(){
        return $this->hasOne('App\Model\Services', 'id', 'attribute_value');
    }
	public function serviceAttribute(){
        return $this->hasOne('App\Model\Services_attribute', 'id', 'attribute_value');
    }
	public function voucher(){
        return $this->hasOne('App\Model\Voucher', 'id', 'attribute_value');
    }
}

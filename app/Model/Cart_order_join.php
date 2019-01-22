<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cart_order_join extends Model
{
    protected $table = 'cart_order_join';
    public $timestamps = false; 
	public function channel(){
        return $this->hasOne('App\Model\Channel', 'id', 'table_parent_id');
    }
	public function getCartOrderJoinChannel(){
        return $this->hasOne('App\Model\Cart_order_join', 'cart_order_parent_id', 'cart_order_parent_id')->where('table','=','channel');
    }
	public function cartOrder(){
        return $this->hasOne('App\Model\Cart_order', 'id', 'cart_order_parent_id');
    }
}

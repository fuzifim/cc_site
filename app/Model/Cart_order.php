<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class Cart_order extends Model
{
    protected $table = 'cart_order';
    public $timestamps = false; 
	public function attribute(){
		if(Auth::check()){
			return $this->hasMany('App\Model\Cart_order_attribute', 'parent_id', 'id')->where('user_id','=',Auth::user()->id);
		}
    }
	public function channel(){
        return $this->hasOne('App\Model\Channel', 'id', 'name');
    }
	public function attributeDomain(){
        return $this->hasMany('App\Model\Cart_order_attribute', 'parent_id', 'id')->where('attribute_type','=','domain');
    }
	public function attributeDomainReOrder(){
        return $this->hasMany('App\Model\Cart_order_attribute', 'parent_id', 'id')->where('attribute_type','=','domainReOrder');
    }
	public function attributeHostingReOrder(){
        return $this->hasMany('App\Model\Cart_order_attribute', 'parent_id', 'id')->where('attribute_type','=','hostingReOrder');
    }
	public function attributeCloudReOrder(){
        return $this->hasMany('App\Model\Cart_order_attribute', 'parent_id', 'id')->where('attribute_type','=','cloudReOrder');
    }
	public function attributeMailServerReOrder(){
        return $this->hasMany('App\Model\Cart_order_attribute', 'parent_id', 'id')->where('attribute_type','=','mail_serverReOrder');
    }
	public function attributeChannel(){
        return $this->hasMany('App\Model\Cart_order_attribute', 'parent_id', 'id')->where('attribute_type','=','channel');
    }
	public function attributeServiceId(){
        return $this->hasOne('App\Model\Cart_order_attribute', 'parent_id', 'id')->where('attribute_type','=','services_attribute_id');
    }
	public function orderAttributeService(){
        return $this->hasOne('App\Model\Cart_order_attribute', 'parent_id', 'id')->where('attribute_type','=','services_attribute_id');
    }
	public function orderAttributeVoucher(){
        return $this->hasOne('App\Model\Cart_order_attribute', 'parent_id', 'id')->where('attribute_type','=','voucher');
    }
	public function orderAttributeQuanlity(){
        return $this->hasOne('App\Model\Cart_order_attribute', 'parent_id', 'id')->where('attribute_type','=','quanlity');
    }
	public function orderJoinChannel(){
        return $this->hasOne('App\Model\Cart_order_join', 'cart_order_parent_id', 'id')->where('table','=','channel');
    }
	public function orderJoinUser(){
        return $this->hasOne('App\Model\Cart_order_join', 'cart_order_parent_id', 'id')->where('table','=','users');
    }
}

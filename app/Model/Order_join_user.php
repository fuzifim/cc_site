<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class Order_join_user extends Model
{
    protected $table = 'order_join_user';
    public $timestamps = false; 
	public function order(){
        return $this->hasOne('App\Model\Order', 'id', 'order_id');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order_bill_napas extends Model
{
    protected $table = 'order_bill_napas';
    protected $fillable =  ['id','transaction_id', 'amount', 'transaction_date','bank_id','transaction_ref','transaction_info','response_code','transaction_status','tranx_type','update_at','date_begin','date_end'];
    public $timestamps = false;
}

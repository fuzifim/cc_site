<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class History_pay_order extends Model
{
    protected $table = 'history_pay_order';
    protected $fillable =  ['id', 'title','amount', 'currencyCode', 'service', 'sender_from', 'sender_to', 'sender_code', 'quantity', 'currencyCode', 'created_at', 'update_at','status','pay_reseller','template_setting_id','date_begin','date_end','pay_hash','transaction_id'];
    public $timestamps = false;
}

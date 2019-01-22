<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $table = 'finance'; 
	protected $fillable = ['id', 'money', 'currency_code', 'pay_type', 'user_id', 'pay_from', 'created_at', 'update_at'];
	public $timestamps = false;
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    
    protected $table = 'regions';
    protected $fillable =  ['id','country', 'iso', 'iso3', 'currency_name', 'phone_prefix','regions_status','regions_create_time','regions_op_time','regions_status'];
    public $timestamps = false;
}

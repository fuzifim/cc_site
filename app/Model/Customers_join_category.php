<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customers_join_category extends Model
{
    protected $table = 'customers_join_category';
    protected $fillable =  ['customers_id','category_id'];
    public $timestamps = false;
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class Order extends Model
{
    protected $table = 'order';
    public $timestamps = false; 
}

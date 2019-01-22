<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class Pay_history extends Model
{
    protected $table = 'pay_history';
    public $timestamps = false; 
}

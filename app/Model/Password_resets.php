<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Password_resets extends Model
{
    protected $table = 'password_resets';
    public $timestamps = false; 
}

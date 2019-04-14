<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class PostToSocial extends Model
{
    protected $table = 'post_to_social';
    public $timestamps = false;
}

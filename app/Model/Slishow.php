<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Slishow extends Model
{
    protected $table = 'slishow';

    protected $fillable =  ['id','name', 'src', 'link','order','status','created_at','updated_at'];
    public $timestamps = false;
}

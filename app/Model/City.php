<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';
    protected $fillable =  ['cityID','cityFullName', 'cityNameSlug', 'cityExcerpt', 'created_at', 'updated_at','statusCity'];
    public $timestamps = false;
}

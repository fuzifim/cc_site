<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag_ads extends Model
{
    protected $table = 'tag_ads';
    protected $fillable =  ['tag_name','tag_slug'];
    public $timestamps = true;
}

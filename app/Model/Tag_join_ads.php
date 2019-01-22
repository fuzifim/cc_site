<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag_join_ads extends Model
{
    protected $table = 'tag_join_ads';
    protected $fillable =  ['tag_slug','ads_id','tag_name'];
    public $timestamps = false;
}

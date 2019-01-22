<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category_option_ads extends Model
{
    protected $table = 'category_option_ads';
    protected $fillable =  ['id','category_option_ads_title', 'category_option_ads_slug','category_option_ads_status','category_icon_span','updated_at','created_at'];
    public $timestamps = false;
}

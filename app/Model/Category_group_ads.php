<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category_group_ads extends Model
{
    protected $table = 'category_join_ads';
    protected $fillable =  ['id','id_ads', 'id_category'];
    public $timestamps = false;
}

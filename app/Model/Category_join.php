<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category_join extends Model
{
    protected $table = 'category_join';
    public $timestamps = false;
	public function category(){
        return $this->hasOne('App\Model\Category', 'id', 'category_parent_id');
    } 
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Category_attribute extends Model
{
    protected $table = 'category_attribute';
    public $timestamps = false; 
	public function category(){
        return $this->hasOne('App\Model\Category', 'id', 'parent_id');
    }
}

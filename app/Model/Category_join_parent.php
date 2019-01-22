<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category_join_parent extends Model
{
    protected $table = 'category_join_parent';
    public $timestamps = false;
	public function category(){
        return $this->hasOne('App\Model\Category', 'id', 'category_id');
    }
}

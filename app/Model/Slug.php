<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Slug extends Model
{
    protected $table = 'slug';
    public $timestamps = false; 
	public function getOneCategory(){
        return $this->hasOne('App\Model\Category', 'id', 'slug_table_id')->where('category_status','=','active');
    }
	public function getOnePost(){
        return $this->hasOne('App\Model\Posts', 'id', 'slug_table_id')->where('posts_status','=','active');
    }
}

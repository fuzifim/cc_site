<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    protected $table = 'category';
    public $timestamps = false;
	public function joinChannel(){
        return $this->hasOne('App\Model\Category_join_channel', 'category_id', 'id');
    }
	public function parent(){
        return $this->hasMany('App\Model\Category', 'id', 'parent_id');
    }
	public function children(){
        return $this->hasMany('App\Model\Category', 'parent_id', 'id');
    }
	public function getSlug(){
        return $this->hasOne('App\Model\Slug', 'slug_table_id', 'id')->where('slug_table','=','category');
    }
	public function options(){
        return $this->hasOne('App\Model\Category_attribute', 'parent_id', 'id')->where('attribute_type','=','options');
    }
	public function postsJoinParent(){
        return $this->hasMany('App\Model\Posts_join_category', 'category_id', 'id');
    }
	public function postsJoinChildren(){
		return $this->hasManyThrough('App\Model\Posts_join_category', 'App\Model\Category', 'parent_id', 'category_id', 'id');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Posts_translate extends Model
{
    protected $table = 'posts_translate';
	public $timestamps = false;
	public function getPost(){
        return $this->hasOne('App\Model\Posts', 'id', 'posts_id')->where('posts_status','=','active');
    }
	public function getSlug(){
        return $this->hasOne('App\Model\Slug', 'slug_table_id', 'posts_id')->where('slug_table','=','posts');
    }
}

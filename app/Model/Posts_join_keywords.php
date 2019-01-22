<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Posts_join_keywords extends Model
{
    protected $table = 'posts_join_keywords';
	public $timestamps = false;
	public function getPost(){
        return $this->hasOne('App\Model\Posts', 'id', 'post_id');
    }
	public function post(){
        return $this->hasOne('App\Model\Posts', 'id', 'post_id');
    }
	public function getKeyword(){
        return $this->hasOne('App\Model\Keywords', 'id', 'keyword_id');
    }
}

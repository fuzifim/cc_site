<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Posts_join_field extends Model
{
    protected $table = 'posts_join_field';
	public $timestamps = false;
	public function getPost(){
        return $this->hasOne('App\Model\Posts', 'id', 'posts_id');
    }
	public function getField(){
		return $this->hasOne('App\Model\Fields', 'id', 'field_id'); 
    }
}

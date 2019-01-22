<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Posts_attribute extends Model
{
    protected $table = 'posts_attribute';
	public $timestamps = false; 
	
	public function user(){
		return $this->hasOne('App\User', 'id', 'posts_attribute_value'); 
    }
	public function media(){
		return $this->hasOne('App\Model\Media', 'id', 'posts_attribute_value'); 
    }
}

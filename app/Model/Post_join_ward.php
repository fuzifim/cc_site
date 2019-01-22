<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post_join_ward extends Model
{
    protected $table = 'post_join_ward';
	public $timestamps = false;
	public function ward(){
        return $this->hasOne('App\Model\Region_ward', 'id', 'ward_id');
    }
}

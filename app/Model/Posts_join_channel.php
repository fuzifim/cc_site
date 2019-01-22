<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Posts_join_channel extends Model
{
    protected $table = 'posts_join_channel';
	public $timestamps = false; 
	public function channel(){
        return $this->hasOne('App\Model\Channel', 'id', 'channel_id');
    }
	public function getPost(){
        return $this->hasOne('App\Model\Posts', 'id', 'posts_id')->where('posts_status','=','active');
    }
	public function getSlug(){
        return $this->hasOne('App\Model\Slug', 'slug_table_id', 'posts_id')->where('slug_table','=','posts');
    }
}

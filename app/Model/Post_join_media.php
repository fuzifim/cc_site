<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post_join_media extends Model
{
    protected $table = 'post_join_media';
	public $timestamps = false;
	public function media(){
        return $this->hasOne('App\Model\Media', 'id', 'media_id');
    }
}

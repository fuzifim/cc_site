<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post_join_user extends Model
{
    protected $table = 'post_join_user';
	public $timestamps = false;
	public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}

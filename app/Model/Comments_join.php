<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Comments_join extends Model
{
    protected $table = 'comments_join';
    public $timestamps = false; 
	public function comment(){
      return $this->hasOne('App\Model\Comments', 'id', 'comment_parent_id'); 
    }
	public function post(){
      return $this->hasOne('App\Model\Posts', 'id', 'table_parent_id'); 
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Comments extends Model
{
    protected $table = 'comments';
    public $timestamps = false; 
	public function children(){
        return $this->hasMany('App\Model\Comments', 'parent_id', 'id');
    }
	public function joinPost(){
      return $this->hasOne('App\Model\Comments_join', 'comment_parent_id', 'id')->where('table', 'posts'); 
    }
	public function author(){
      return $this->hasOne('App\Model\Comments_attribute', 'parent_id', 'id')->where('attribute_type', 'author'); 
    }
	public function name(){
      return $this->hasOne('App\Model\Comments_attribute', 'parent_id', 'id')->where('attribute_type', 'comment_name'); 
    }
	public function phone(){
      return $this->hasOne('App\Model\Comments_attribute', 'parent_id', 'id')->where('attribute_type', 'comment_phone'); 
    }
	public function email(){
      return $this->hasOne('App\Model\Comments_attribute', 'parent_id', 'id')->where('attribute_type', 'comment_email'); 
    }
	public function attribute(){
      return $this->hasOne('App\Model\Comments_attribute', 'parent_id', 'id'); 
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Fields extends Model
{
    protected $table = 'fields';
    public $timestamps = false; 
	public function parent(){
        return $this->hasMany('App\Model\Fields', 'id', 'parent_id');
    }
	public function children(){
        return $this->hasMany('App\Model\Fields', 'parent_id', 'id');
    }
	public function postsJoinParent(){
        return $this->hasMany('App\Model\Posts_join_field', 'field_id', 'id');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post_join_district extends Model
{
    protected $table = 'post_join_district';
	public $timestamps = false;
	public function district(){
        return $this->hasOne('App\Model\Region_district', 'id', 'district_id');
    }
}

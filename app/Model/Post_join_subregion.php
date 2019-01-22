<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post_join_subregion extends Model
{
    protected $table = 'post_join_subregion';
	public $timestamps = false;
	public function subregion(){
        return $this->hasOne('App\Model\Subregions', 'id', 'subregion_id');
    }
}

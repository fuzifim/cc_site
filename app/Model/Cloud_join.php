<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cloud_join extends Model
{
    protected $table = 'cloud_join';
	public $timestamps = false; 
	public function cloud(){
        return $this->hasOne('App\Model\Cloud', 'id', 'cloud_parent_id');
    }
	public function channel(){
        return $this->hasOne('App\Model\Channel', 'id', 'table_parent_id');
    }
}

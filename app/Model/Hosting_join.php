<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Hosting_join extends Model
{
    protected $table = 'hosting_join';
	public $timestamps = false; 
	public function hosting(){
        return $this->hasOne('App\Model\Hosting', 'id', 'hosting_parent_id');
    }
	public function channel(){
        return $this->hasOne('App\Model\Channel', 'id', 'table_parent_id');
    }
}

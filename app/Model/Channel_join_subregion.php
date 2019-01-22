<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Channel_join_subregion extends Model
{
    protected $table = 'channel_join_subregion';
    public $timestamps = false; 
	public function subregion(){
        return $this->hasOne('App\Model\Subregions', 'id', 'subregion_id');
    } 
}

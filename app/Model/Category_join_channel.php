<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category_join_channel extends Model
{
    protected $table = 'category_join_channel';
    public $timestamps = false;
	public function category(){
        return $this->hasOne('App\Model\Category', 'id', 'category_id');
    }
	public function channel(){
        return $this->hasOne('App\Model\Channel', 'id', 'channel_id');
    }
}

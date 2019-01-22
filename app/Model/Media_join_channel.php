<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Media_join_channel extends Model
{
    protected $table = 'media_join_channel';
	public $timestamps = false; 
	public function media(){
        return $this->hasOne('App\Model\Media', 'id', 'media_id');
    }
}

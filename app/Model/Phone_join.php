<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Phone_join extends Model
{
    protected $table = 'phone_join';
    public $timestamps = false;
	
	public function phone(){
        return $this->hasOne('App\Model\Phone', 'id', 'phone_parent_id');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Services_join extends Model
{
    protected $table = 'services_join';
    public $timestamps = false; 
	public function attribute(){
        return $this->hasOne('App\Model\Services_attribute', 'parent_id', 'services_parent_id');
    }
}

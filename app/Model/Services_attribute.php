<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Services_attribute extends Model
{
    protected $table = 'services_attribute';
    public $timestamps = false; 
	public function service(){
        return $this->hasOne('App\Model\Services', 'id', 'parent_id');
    }
}

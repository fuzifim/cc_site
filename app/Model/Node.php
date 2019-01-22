<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Node extends Eloquent {
	protected $connection = 'mongodb';
    protected $collection = 'node'; 
	//public $timestamps = false; 
	public function joinField(){
        return $this->hasOne('App\Model\Fields', 'id', 'field');
    }
}

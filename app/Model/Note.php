<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Note extends Eloquent {
	protected $connection = 'mongodbcc';
    protected $collection = 'note'; 
	public $timestamps = false; 
}

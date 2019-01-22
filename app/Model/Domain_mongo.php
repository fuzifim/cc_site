<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Domain_mongo extends Eloquent {
	protected $connection = 'mongodb';
    protected $collection = 'domains';

}

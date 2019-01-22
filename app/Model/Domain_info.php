<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Domain_info extends Eloquent {
	protected $connection = 'mongodb';
    protected $collection = 'domain_info';

}

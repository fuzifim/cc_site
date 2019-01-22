<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Keyword_info extends Eloquent {
	protected $connection = 'mongodb';
    protected $collection = 'keyword_info';

}

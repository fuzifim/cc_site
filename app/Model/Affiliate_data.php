<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Affiliate_data extends Eloquent {
	protected $connection = 'mongodb';
    protected $collection = 'affiliate_data';

}

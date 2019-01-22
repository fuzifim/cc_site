<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;
class Affiliate_feed extends Model
{
	use ElasticquentTrait;
	//protected $connection = 'mysqlzcom';
    protected $table = 'affiliate_feed';
    public $timestamps = false; 
}

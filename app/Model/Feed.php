<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//use Elasticquent\ElasticquentTrait;
class Feed extends Model
{
	//use ElasticquentTrait;
	//protected $connection = 'mysqlzcom';
    protected $table = 'feed';
	public $timestamps = false; 
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;
class Feed_rss extends Model
{
	use ElasticquentTrait;
	//protected $connection = 'mysqlzcom';
    protected $table = 'feed_rss';
    public $timestamps = false; 
	function getIndexName()
	{
		return 'feed_index';
	}
}

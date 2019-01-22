<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;
class News extends Model
{
	use ElasticquentTrait;
	protected $connection = 'mysqlzcom';
    protected $table = 'news';
	public $timestamps = false; 
	function getIndexName()
	{
		return 'news_index';
	}
	public function joinField(){
        return $this->hasOne('App\Model\Fields', 'id', 'field');
    }
}

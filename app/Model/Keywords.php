<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;
class Keywords extends Model
{
	use ElasticquentTrait;
	//protected $connection = 'mysqlzcom';
    protected $table = 'keywords';
    public $timestamps = false; 
	public function joinPosts(){
      return $this->hasMany('App\Model\Posts_join_keywords', 'keyword_id', 'id'); 
    }
	public function listSearch(){
        return $this->hasOne('App\Model\Keywords_attribute', 'keyword_id', 'id')->where('type','=','post_search');
    }
	public function videoSearch(){
        return $this->hasOne('App\Model\Keywords_attribute', 'keyword_id', 'id')->where('type','=','video_search');
    }
}

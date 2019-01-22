<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;
class Domain extends Model
{
	use ElasticquentTrait;
	//protected $connection = 'mysqlzcom';
    protected $table = 'domains_z';
	public $timestamps = false; 
	function getIndexName()
	{
		return 'domain_index';
	}
	public function whois(){
        return $this->hasOne('App\Model\Domain_whois', 'domain_id', 'id');
    }
	public function domainJoinChannel(){
        return $this->hasOne('App\Model\Domain_join_channel', 'domain_id', 'id');
    }
	public function joinService(){
        return $this->hasOne('App\Model\Domain_join', 'domain_parent_id', 'id')->where('table','=','services');
    }
	public function serviceAttribute(){
        return $this->hasOne('App\Model\Services_attribute', 'id', 'service_attribute_id');
    }
	public function attributeAuthor(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','author');
    }
	public function title(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','title');
    }
	public function description(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','description');
    }
	public function keywords(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','keywords');
    }
	public function image(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','image');
    }
	public function h1tag(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','h1tag');
    }
	public function h2tag(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','h2tag');
    }
	public function h3tag(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','h3tag');
    }
	public function h4tag(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','h4tag');
    }
	public function h5tag(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','h5tag');
    }
	public function atag(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','atag');
    }
	public function ip(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','ip');
    }
	public function postSearch(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','post_search');
    }
	public function videoSearch(){
        return $this->hasOne('App\Model\Domain_attribute', 'parent_id', 'id')->where('attribute_type','=','video_search');
    }
}

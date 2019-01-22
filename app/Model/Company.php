<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;
class Company extends Model
{
	use ElasticquentTrait;
	//protected $connection = 'mysqlzcom';
    protected $table = 'company';
    public $timestamps = false; 
	function getIndexName()
	{
		return 'company_index';
	}
	public function joinAddress(){
        return $this->hasMany('App\Model\Company_join_address', 'company_id', 'id');
    }
	public function joinChannel(){
        return $this->hasMany('App\Model\Company_join_channel', 'company_id', 'id');
    }
	public function joinChannelParent(){
        return $this->hasOne('App\Model\Company_join_channel_parent', 'company_id', 'id');
    }
}

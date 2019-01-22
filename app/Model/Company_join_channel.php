<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Company_join_channel extends Model
{
	//protected $connection = 'mysqlzcom';
    protected $table = 'company_join_channel';
    public $timestamps = false; 
	
	public function company(){
        return $this->hasOne('App\Model\Company', 'id', 'company_id');
    }
	public function channel(){
        return $this->hasOne('App\Model\Channel', 'id', 'channel_id');
    }
}

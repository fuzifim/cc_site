<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Domain_join_channel extends Model
{
    protected $table = 'domain_join_channel';
    public $timestamps = false; 
	public function channel(){
        return $this->hasOne('App\Model\Channel', 'id', 'channel_id');
    }
	public function domain(){
        return $this->hasOne('App\Model\Domain', 'id', 'domain_id');
    }
	public function domainPrimary(){
        return $this->hasOne('App\Model\Domain', 'id', 'domain_id')->where('domain_location','local');
    }
	public function service(){
        return $this->hasOne('App\Model\Services', 'id', 'table_parent_id');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Domain_join extends Model
{
    protected $table = 'domain_join';
    public $timestamps = false; 
	
	public function channel(){
        return $this->hasOne('App\Model\Channel', 'id', 'table_parent_id');
    }
	public function domain(){
        return $this->hasOne('App\Model\Domain', 'id', 'domain_parent_id');
    }
	public function domainPrimary(){
        return $this->hasOne('App\Model\Domain', 'id', 'domain_parent_id');
    }
	public function service(){
        return $this->hasOne('App\Model\Services', 'id', 'table_parent_id');
    }
}

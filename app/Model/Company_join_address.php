<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Company_join_address extends Model
{
	//protected $connection = 'mysqlzcom';
    protected $table = 'company_join_address';
    public $timestamps = false; 
	public function address(){
        return $this->hasOne('App\Model\Address', 'id', 'address_id');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Company_join extends Model
{
	protected $connection = 'mysqlzcom';
    protected $table = 'company_join';
    public $timestamps = false; 
	
	public function company(){
        return $this->hasOne('App\Model\Company', 'id', 'company_parent_id');
    }
}

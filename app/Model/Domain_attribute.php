<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Domain_attribute extends Model
{
	protected $connection = 'mysqlzcom';
    protected $table = 'domain_attribute';
    public $timestamps = false;
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Domain_whois extends Model
{
	protected $connection = 'mysqlzcom';
    protected $table = 'domain_whois';
    public $timestamps = false;
}

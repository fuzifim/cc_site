<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Keywords_attribute extends Model
{
	protected $connection = 'mysqlzcom';
    protected $table = 'keywords_attribute';
    public $timestamps = false; 
}

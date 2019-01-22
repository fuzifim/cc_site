<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Domain_crv extends Model
{
	protected $connection = 'crviet';
    protected $table = 'domain';
	public $timestamps = false; 
}

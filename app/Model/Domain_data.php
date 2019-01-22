<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Domain_data extends Model
{
	protected $connection = 'mysqlzcom';
    protected $table = 'domain_data';
    public $timestamps = true;
	protected $fillable = ['parent_id', 'content', 'created_at', 'updated_at'];
}

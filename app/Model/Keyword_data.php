<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Keyword_data extends Model
{
	protected $connection = 'mysqlzcom';
    protected $table = 'keyword_data';
    public $timestamps = true;
	protected $fillable = ['parent_id', 'content','affiliate', 'created_at', 'updated_at'];
}

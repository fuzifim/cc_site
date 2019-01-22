<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Member_join extends Model
{
    protected $table = 'member_join';
	protected $fillable =  ['id','user_id', 'table_join', 'table_id', 'created_at', 'updated_at', 'status'];
	public $timestamps = false;
}

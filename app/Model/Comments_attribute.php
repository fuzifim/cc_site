<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Comments_attribute extends Model
{
    protected $table = 'comments_attribute';
    public $timestamps = false; 
	public function user(){
      return $this->hasOne('App\User', 'id', 'attribute_value'); 
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class Pay_history_join_user extends Model
{
    protected $table = 'pay_history_join_user';
    public $timestamps = false;  
	public function history(){
        return $this->hasOne('App\Model\Pay_history', 'id', 'pay_history_id');
    }
}

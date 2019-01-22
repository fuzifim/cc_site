<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth; 
class SiteLink extends Model
{
    protected $table = 'site';
    public $timestamps = false; 
	public function title(){
        return $this->hasOne('App\Model\Site_attribute', 'site_id', 'id')->where('type','=','title');
    }
	public function description(){
        return $this->hasOne('App\Model\Site_attribute', 'site_id', 'id')->where('type','=','description');
    }
	public function keywords(){
        return $this->hasOne('App\Model\Site_attribute', 'site_id', 'id')->where('type','=','keywords');
    }
	public function image(){
        return $this->hasOne('App\Model\Site_attribute', 'site_id', 'id')->where('type','=','image');
    }
}

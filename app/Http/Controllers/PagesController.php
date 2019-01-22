<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Request;
use Illuminate\Support\Str;
use Auth;
use App\User; 
use Theme;
use Redirect; 
use Adfly; 
class PagesController extends ConstructController
{
	public function __construct(){
		parent::__construct(); 
	}
	public function webDesign(){
		$return=array(); 
		return $this->_theme->scope('pages.web_design', $return)->render();
	}
	public function domain(){
		$return=array(); 
		return $this->_theme->scope('pages.domain', $return)->render();
	}
	public function email(){
		$return=array(); 
		return $this->_theme->scope('pages.email', $return)->render();
	}
	public function hosting(){
		$return=array(); 
		return $this->_theme->scope('pages.hosting', $return)->render();
	}
	public function cloud(){
		$return=array(); 
		return $this->_theme->scope('pages.cloud', $return)->render();
	}
	public function Price(){
		$return=array(); 
		return $this->_theme->scope('pages.price', $return)->render();
	}
	public function gotoUrl(){
		$this->_theme=Theme::uses('goto')->layout('default'); 
		$url=$this->_parame['url']; 
		$return=array(
			'url'=>$url
		); 
		return Redirect::to($url);
		return $this->_theme->scope('goto', $return)->render();
	}
}
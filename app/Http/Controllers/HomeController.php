<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User; 
use App\Permission;
use App\Role; 
use App\Model\Channel_role;
use App\Model\Company; 
use App\Model\Company_join; 
use App\Model\Company_attribute; 
use App\Model\Channel; 
use App\Model\Channel_join;
use App\Model\Channel_attribute; 
use App\Model\Domain;
use App\Model\Domain_join;
use App\Model\Domain_attribute;
use App\Model\Fields;
use App\Model\Posts;
use App\Model\Posts_join;
use App\Model\Posts_attribute; 
use App\Model\Category;
use App\Model\Category_join;
use App\Model\Slug;
use App\Model\Media; 
use App\Model\Media_join;
use App\Model\Users; 
use App\Model\Users_join; 
use App\Model\Email;
use App\Model\Email_join;
use App\Model\Phone;
use App\Model\Phone_join; 
use App\Model\Like; 
use App\Model\Messages; 
use App\Model\Comments; 
use App\Model\Comments_join; 
use App\Model\Comments_attribute;
use Imagick;
use File;
use Youtube; 
use Agent;
use Auth;
use Input;
use DB;
use Hash;
use Closure;
use Validator;
use Session;
use Redirect;
use Mail;
use WebService;
use Site;
use Response;
use Carbon\Carbon;
use Theme;
use URL;
use Route;
use Parser; 


class HomeController extends Controller
{
	protected $_domain; 
	protected $_channel; 
	protected $_theme; 
	protected $_parame; 
	public function __construct(){
		$this->_parame=Route::current()->parameters(); 
		$this->_domain=WebService::getDomainMain($this->_parame['domain']); 
		$this->beforeFilter(function()
		{
		   if(empty($this->_domain->domain)){
				return view('404'); 
			}else{
				$this->_theme=Theme::uses('main')->layout('default'); 
			}
		});
	}
    public function index()
    {
        $return = array();
		return $this->_theme->scope('home', $return)->render();
    }
}

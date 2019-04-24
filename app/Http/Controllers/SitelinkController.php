<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Input;
use App\User;
use Auth;
use Illuminate\Support\Str;
use Validator;
use DB; 
use Cache; 
use Response; 
use Carbon\Carbon; 
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Model\SiteLink;
use App\Model\Site_attribute;
use App\Model\Site_url;
use App\Model\Company; 
use App\Model\Keywords;
use App\Model\Domain; 
use App\Model\Domain_attribute; 
use App\Model\Node; 
use WebService;
use Redirect; 
use App\Http\Controllers\DomainController;
use App\Http\Controllers\KeywordsController;
class SitelinkController extends ConstructController
{
	public function __construct(){
		parent::__construct(); 
	}
	public function list(Request $request){
		$page = $request->has('page') ? $request->query('page') : 1; 
		$getNewDomain = Cache::store('file')->remember('getNewDomain'.$page, 5, function()
			{
				return Node::where('type','domain')->where('status','=','active')
					->orderBy('updated_at','desc')
					->paginate(20); 
			}); 
		$getCompany = Cache::store('file')->remember('getNewCompanyUpdate_'.$page,5, function()
			{
				return Node::where('type','company')->where('status','!=','delete')
					->orderBy('updated_at','desc')
					->paginate(10); 
			}); 
		$return= array(
				'getSite'=>$getNewDomain, 
				'getCompany'=>$getCompany
			);
		return $this->_theme->scope('sitelink.list', $return)->render();
	}
	public function show(){
		$siteId=$this->_parame['slug']; 
		//return Redirect::to('http://'.$siteId.'.'.config('app.url'), 301);
        return redirect()->route('domain.info', array(config('app.url'),$siteId),301);
	}
}
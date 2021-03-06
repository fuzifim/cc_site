<?php 
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Request;
use Auth;
use App\Role; 
use App\Model\Regions;
use App\Model\Channel;
use App\Model\Channel_role;
use App\Model\Finance; 
use App\Model\Urlreferer;
use App\User;
use App\Model\Domain;
use App\Model\Domain_mongo;
use App\Model\Domain_join;
use App\Model\Domain_join_channel;
use App\Model\Domain_attribute;
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use Theme;
use Route; 
use Redirect; 
use WebService; 
use Carbon\Carbon; 
use Lang; 
use Session;
use Cookie;
use File; 
use Cache; 
use App\Model\Node;
use DB;
class ConstructController extends Controller
{
	public $_region; 
	public $_domainName; 
	public $_domain; 
	public $_siteSuccess; 
	public $_channel; 
	public $_channelParent; 
	public $_channelColor; 
	public $_channelCategory; 
	public $_theme; 
	public $_parame; 
	public $_security=false;
	public $_limitSize;
	public $_totalSize; 
	public $_percenSize; 
	public $_permissionSize; 
	public $_limitPosts;
	public $_percenPosts; 
	public $_totalPosts; 
	public $_domainPrimary; 
	public $_domainParentPrimary; 
	public $_sociallinkFanpageFacebook; 
	public $_socialLinkGoogle; 
	public $_socialLinkZalo; 
	public $_parser; 
	public $_wordBlacklist; 
	public $_financeUserTotal=0; 
	public $_fullUrl; 
	protected $_user;
	public $_pieces=array(); 
	public $_rulesDomain;
	public $_newKeyword=array();
	public $_detectLang = 'vn';
	public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->userSecurity();
            return $next($request);
        });
        if(strtolower($_SERVER['GEOIP_COUNTRY_CODE'])!='vn' && !empty($_SERVER['GEOIP_COUNTRY_CODE'])){
            $this->_detectLang = 'en';
        }
		$this->_parame=Route::current()->parameters(); 
		$this->_rulesDomain = Cache::store('file')->rememberForever('rulesDomain', function()
		{
			$pdp_url = public_path('data/public_suffix_list.dat.txt');
			$rules = \Pdp\Rules::createFromPath($pdp_url); 
			return $rules; 
		});
		$parsedUrl=parse_url(Request::url()); 
		if(!empty($parsedUrl['host'])){
			$checkDomain=str_replace('www.','',$parsedUrl['host']);
            $domainType='';
            if (Cache::store('memcached')->has('domain_type_'.$checkDomain)) {
                $domainType=Cache::store('memcached')->get('domain_type_'.$checkDomain);
            }
            if($domainType=='domainInfo'){
                if(!empty(Cookie::get('shared_cookie'))){
                    Session::setId(Cookie::get('shared_cookie'));
                    Session::start();
                }
                $this->_siteSuccess='redirect';
                $this->_theme=Theme::uses('main')->layout('default');
                $this->_channel = Cache::store('memcached')->remember('channelPrimary_new',1, function()
                {
                    if(config('app.env')=='local'){
                        return Channel::find(1);
                    }else{
                        return Channel::find(2);
                    }
                });
            }else{
                $this->_region = Cache::store('file')->remember('region',1, function()
                {
                    return Regions::find(704);
                });
                $this->_domain = Cache::store('memcached')->remember('thisDomain'.$checkDomain, 5, function() use($checkDomain)
                {
                    return Domain::where('domain','=',$checkDomain)->first();
                });
                if(!empty($this->_domain->domain)){
                    $this->_siteSuccess='infoChannel';
                    $this->_channel = $this->_domain->domainJoinChannel->channel;
                    if($this->_channel->channel_parent_id==0){
                        $this->_theme=Theme::uses('main')->layout('default');
                        if(config('app.env')!='local' && $parsedUrl['scheme']!='https'){
                            return redirect()->secure(Request::getRequestUri())->send();
                        }
                    }else{
                        $this->_theme=Theme::uses('control')->layout('default');
                    }
                }else{
                    $this->_siteSuccess='redirect';
                    if(!empty(Cookie::get('shared_cookie'))){
                        Session::setId(Cookie::get('shared_cookie'));
                        Session::start();
                    }
                    $this->_theme=Theme::uses('main')->layout('default');
                    $this->_channel = Cache::store('memcached')->remember('channelPrimary_new',1, function()
                    {
                        if(config('app.env')=='local'){
                            return Channel::find(1);
                        }else{
                            return Channel::find(2);
                        }
                    });
                    Cache::store('memcached')->remember('domain_type_'.$checkDomain, 500, function()
                    {
                        return 'domainInfo';
                    });
                }
            }
		}
		if(!empty($this->_domain->domain)){
            $this->_domainPrimary = $this->_domain->domain;
        }else{
            $this->_domainPrimary=config('app.url');
        }
        $this->_domainParentPrimary = config('app.url');
		if($this->_channel->channel_parent_id==0){
		    if($this->_domainPrimary!=config('app.url')){
                return redirect()->to('https://'.config('app.url').Request::getRequestUri())->send();
            }
//            $this->_newKeyword=Cache::store('memcached')->remember('newKeyword', 1, function()
//            {
//                return DB::connection('mongodb')->collection('mongo_keyword')
//                    ->where('craw_next','exists',true)
//                    ->orderBy('updated_at','desc')
//                    ->limit(20)->get();
//            });
        }
        if($this->_channel->channel_parent_id!=0){
            $getServiceValue=json_decode($this->_channel->channelService->attribute_value);
            $this->_limitSize=$getServiceValue->limit_cloud;
            $this->_totalPosts=count($this->_channel->joinPosts);
            $this->_limitPosts=$getServiceValue->limit_post;
            $this->_totalSize=0;
            $this->_totalSize = Cache::store('memcached')->remember('channelTotalSize_new_'.$this->_channel->id, 1, function()
            {
                if(count($this->_channel->joinMedia)>0){
                    foreach($this->_channel->joinMedia as $joinMedia){
                        $this->_totalSize=$this->_totalSize+$joinMedia->media->media_size;
                    }
                }
            });
            $this->_percenSize=(Webservice::formatBytesToMb($this->_totalSize)/$this->_limitSize)*100;
            $this->_percenPosts=($this->_totalPosts/$this->_limitPosts)*100;
            $this->_channelColor=(!empty($this->_channel->channelAttributeColor->channel_attribute_value)) ? json_decode($this->_channel->channelAttributeColor->channel_attribute_value) : false;
            $this->_channelCategory=$this->_channel->getCategory;
        }
        if(!empty($this->_channel->channelAttributeColor->channel_attribute_value)){
            $contentAttributeDecode=json_decode($this->_channel->channelAttributeColor->channel_attribute_value);
            if(!empty($contentAttributeDecode->fanpageFacebook)){
                $linkFanpageFacebook = parse_url($contentAttributeDecode->fanpageFacebook);
                $this->_sociallinkFanpageFacebook=str_replace('/', "", $linkFanpageFacebook['path']);
            }
            if(!empty($contentAttributeDecode->zaloAccount)){
                $this->_socialLinkZalo=$contentAttributeDecode->zaloAccount;
            }
        }
        $this->viewShare();
    }
    private function userSecurity()
    {
        if(Auth::check()){
            $this->_user=Auth::user();
            $getFinance=Finance::where('user_id','=',$this->_user->id)->get();
            if(count($getFinance)>0){
                foreach($getFinance as $finance){
                    if($finance->pay_type=='add'){
                        $this->_financeUserTotal=$this->_financeUserTotal+$finance->money;
                    }else if($finance->pay_type=='remove'){
                        $this->_financeUserTotal=$this->_financeUserTotal-$finance->money;
                    }
                }
            }
            if($this->_user->hasRole(['admin', 'manage'])){
                $this->_security=true;
            }
            $getRoleChannel=Channel_role::where('parent_id','=',$this->_channel->id)->where('user_id','=',$this->_user->id)->first();
            if(!empty($getRoleChannel->id)){
                $getRole=Role::findOrFail($getRoleChannel->role_id);
                $role_permissions = $getRole->perms()->get();
                foreach ($role_permissions as $permission) {
                    if ($permission->name == 'manager_channel') {
                        $this->_security=true;
                    }
                }
            }
        }
        $this->viewShare();

    }
    private function viewShare(){
        view()->share(
            'channel',array(
                'detectLang'=>$this->_detectLang,
                'region'=>$this->_region,
                'financeUserTotal'=>$this->_financeUserTotal,
                'limitSize'=>$this->_limitSize,
                'totalSize'=>Webservice::formatBytesToMb($this->_totalSize),
                'percenSize'=>$this->_percenSize,
                'limitPosts'=>$this->_limitPosts,
                'totalPosts'=>$this->_totalPosts,
                'percenPosts'=>$this->_percenPosts,
                'domain'=>$this->_domain,
                'info'=>$this->_channel,
                'security'=>$this->_security,
                'category'=>$this->_channelCategory,
                'theme'=>$this->_theme,
                'domainPrimary'=>$this->_domainPrimary,
                'domainParentPrimary'=>$this->_domainParentPrimary,
                'nameFanpageFacebook'=>mb_strtolower($this->_sociallinkFanpageFacebook),
                'zaloAccount'=>$this->_socialLinkZalo,
                'color'=>$this->_channelColor,
                '_parser'=>$this->_parser
            )
        );
    }
}
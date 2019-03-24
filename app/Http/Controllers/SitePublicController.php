<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request; 
use Illuminate\Support\Str;
use App\User;
use App\Model\Users_attribute;  
use App\Permission;
use App\Role; 
use App\Model\Channel_role;
use App\Model\History; 
use App\Model\Services; 
use App\Model\Services_attribute; 
use App\Model\Keywords;
use App\Model\Company; 
use App\Model\Company_join; 
use App\Model\Company_join_channel; 
use App\Model\Company_attribute; 
use App\Model\Channel; 
use App\Model\Channel_join; 
use App\Model\Channel_join_region;
use App\Model\Channel_join_subregion;
use App\Model\Channel_join_district;
use App\Model\Channel_join_ward;
use App\Model\Channel_join_field;
use App\Model\Channel_attribute; 
use App\Model\Channel_join_address;
use App\Model\Channel_join_email;
use App\Model\Channel_join_phone;
use App\Model\Domain;
use App\Model\Domain_join;
use App\Model\Domain_join_channel;
use App\Model\Domain_attribute;
use App\Model\Domain_whois;
use App\Model\Fields;
use App\Model\Posts;
use App\Model\Posts_join;
use App\Model\Posts_attribute; 
use App\Model\Posts_join_keywords;
use App\Model\Category;
use App\Model\Category_join;
use App\Model\Slug;
use App\Model\Media; 
use App\Model\Media_join; 
use App\Model\Media_join_post; 
use App\Model\Media_join_channel;
use App\Model\Users_join; 
use App\Model\Address;
use App\Model\Address_join_region;
use App\Model\Address_join_subregion;
use App\Model\Address_join_district;
use App\Model\Address_join_ward;
use App\Model\Email;
use App\Model\Email_join;
use App\Model\Phone;
use App\Model\Phone_join; 
use App\Model\Messages; 
use App\Model\Region_district;
use App\Model\Region_ward;
use App\Model\Regions;
use App\Model\Subregions; 
use App\Model\News; 
use App\Model\Feed_rss;
use Agent;
use Auth;
use Input;
use DB;
use Closure;
use Validator;
use Redirect;
use Mail;
use WebService;
use Site;
use Response;
use Carbon\Carbon;
use Theme;
use URL;
use Route;
use FFMpeg; 
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use App\Http\Controllers\DomainController;
use App\Http\Controllers\KeywordsController; 
use DonatelloZa\RakePlus\RakePlus;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client; 
use File;
use App\Model\Domain_info; 
use App\Model\Domain_data; 
use App\Model\Affiliate_feed; 
use App\Model\Node; 
use Session; 
use Cache; 
use Lullabot\AMP\AMP;
use Lullabot\AMP\Validate\Scope; 
class SitePublicController extends ConstructController
{
	public $_site=array(); 
	public $_domainAddNew; 
	public $_domainAddNewId; 
	public $_dateFolder; 
	public $_domainContent=array(); 
	public $_postSearch=array();  
	public $_getPostSearch=array(); 
	public $_getVideoSearch=array(); 
	public $_videoSearch=array(); 
	public $_domain_h1=array(); 
	public $_domain_h2=array(); 
	public $_domain_h3=array(); 
	public $_domain_h4=array(); 
	public $_domain_a=array(); 
	public $_domain_ip=''; 
	public $_domainWhois; 
	public $_domain_title; 
	public $_domain_description; 
	public $_domain_keywords; 
	public $_setKeyword=array(); 
	public $_affiliate=array(); 
	public $_domainSearch=array(); 
	public $_newsSearch=array(); 
	public $_postsSearch=array(); 
	public $_feedSearch=array(); 
	public $_ipDetail=array(); 
	public $_title; 
	public $_description; 
	public $_keyword; 
	public $_basicInfo; 
	public $_websiteInfo; 
	public $_semrushMetrics; 
	public $_dnsReport; 
	public $_ipAddressInfo; 
	public $_whoisRecord;
	public $_domainInfo;
	public function __construct(){
		parent::__construct(); 
	}
	public function index(Request $request)
    {
//        $getDomain=DB::connection('mongodb')->collection('mongo_domain')
//            //->where('type','site')
//            ->orderBy('updated_at','desc')
//            ->limit(10)->get();
//        dd($getDomain);
		$pieces=$this->_pieces; 
		if($this->_siteSuccess=='infoChannel'){
			$error=''; 
			if(Session::has('view_channel')){
				$sessionHistory=Session::get('view_channel'); 
				if(!empty($sessionHistory['created_at'])){
					if(Carbon::parse($sessionHistory['created_at'])->addMinutes(5) > Carbon::now()->format('Y-m-d H:i:s')){
						$error='Mỗi lượt xem cách nhau 5 phút. Lần xem gần đây nhất của bạn cách đây '.WebService::time_request($sessionHistory['created_at']); 
					}
				}
			}
			if(empty($error)){
				$this->_channel->increment('channel_view',1); 
				if(Session::has('view_channel')){
					Session::forget('view_channel');
				}
				Session::put('view_channel', [ 
					'ip' => $request->ip(), 
					'created_at' => Carbon::now()->format('Y-m-d H:i:s')
				]); 
			}
			if($this->_channel->channel_parent_id==0){
				$getChannelAll = Cache::store('memcached')->remember('getChannelAll', 5, function()
				{
					return Channel::where('channel_status','!=','delete')->get(); 
				});
				$getUserAll = Cache::store('memcached')->remember('getUserAll', 5, function()
				{
					return User::all(); 
				});
				$return = array(
					'getChannelAll'=>$getChannelAll, 
					'getUserAll'=>$getUserAll
				);
				return $this->_theme->scope('home', $return)->render();
			}else{
				$postListNew=array(); 
				$return = array(
					'postListNew'=>$postListNew, 
				);
				return $this->_theme->scope('home', $return)->render();
			}
		}else if($this->_siteSuccess=='redirect'){ 
			$parsedUrl=parse_url($request->url()); 
			$checkDomain=str_replace('.'.config('app.url'),'',$parsedUrl['host']);
            $checkRecore=substr($checkDomain, -2);
            if($checkRecore=='.d'){
                $checkDomain=substr($checkDomain, 0, -2);
            }else{
                return Redirect::to('http://'.$checkDomain.'.d.'.config('app.url'),301);
            }
			$this->_pieces = explode("-", $checkDomain); 
			if(!empty($this->_pieces[0]) && $this->_pieces[0]=='post'){
				//$this->_siteSuccess='infoPost'; 
			}else if(!empty($this->_pieces[0]) && $this->_pieces[0]=='news'){
				return Redirect::to('https://cungcap.net/news/'.$this->_pieces[1].'/old',301); 
			}else if(!empty($this->_pieces[0]) && $this->_pieces[0]=='feed'){ 
				//$this->_siteSuccess='infoFeed'; 
			}else if(!empty($this->_pieces[0]) && $this->_pieces[0]=='com'){
				return Redirect::to('https://cungcap.net/com/'.$this->_pieces[1].'/old',301); 
			}elseif(!empty($this->_pieces[0]) && $this->_pieces[0]=='www'){
				return Redirect::to('https://cungcap.net/',301);
			}else{
			    $this->_domainInfo=$checkDomain;
			    return $this->DomainInfo();
			}
		}
	}
	public function DomainInfo(){
        if(!empty($this->_domainInfo)){
            $domain = Cache::store('memcached')->remember('infoDomain_'.base64_encode($this->_domainInfo), 1, function()
            {
                return DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($this->_domainInfo))->first();
            });
            if(!empty($domain['domain'])){
                $newDomain=Cache::store('memcached')->remember('newDomain', 1, function()
                {
                    return DB::connection('mongodb')->collection('mongo_domain')
                        //->where('status','active')
                        ->orderBy('updated_at','desc')
                        ->limit(20)->get();
                });
                $return=array(
                    'domain'=>$domain,
                    'newDomain'=>$newDomain
                );
                return $this->_theme->scope('domain.viewInfo', $return)->render();
            }else{
                echo 'domain not found';
            }
        }
    }
	public function addNewDomainInfomation()
    {
		//return 'false';
		$checkHistory=History::where('history_type','=','get_content')->first();
		if(!empty($checkHistory->created_at)){
			if(Carbon::parse($checkHistory->created_at)->addSecond(2)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
				History::where('history_type','=','get_content')->delete(); 
				$listHistory=array(
					'history_type'=>'get_content', 
					'parent_id'=>0, 
					'author'=>0,
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				); 
				History::insertGetId($listHistory); 
				$newDomain=new DomainController(); 
				$newDomain->_domainRegister=$this->_domainAddNew; 
				$newDomain->_domainRegisterPrimary='none'; 
				$newDomain->_domainRegisterLocation='outside'; 
				$newDomain->_domainRegisterServiceId=''; 
				$newDomain->_domainRegisterStatus='pending'; 
				$newDomain->_domainRegisterCreatedAt=Carbon::now()->format('Y-m-d H:i:s'); 
				$newDomain->_domainRegisterUpdateAt=Carbon::now()->format('Y-m-d H:i:s'); 
				$newDomain->_domainRegisterDateBegin=''; 
				$newDomain->_domainRegisterDateEnd=''; 
				$idDomain=$newDomain->addDomain(); 
				if($idDomain){
					$this->_domainAddNewId=$idDomain; 
					$this->_site=Domain::find($idDomain); 
					//$this->_dateFolder=$this->makeDir(); 
					//$this->_site->path_data=$this->_dateFolder; 
					$this->_site->insert_by='crawler'; 
					$this->_site->save(); 
					//return $this->_site; 
					return $this->addInForDomain(); 
				}else{
					return 'false'; 
				}
			}else{
				return 'false';
			}
		}else{
			$listHistory=array(
				'history_type'=>'get_content', 
				'parent_id'=>0, 
				'author'=>0,
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
			); 
			History::insertGetId($listHistory); 
			return 'false'; 
		}
		
	}
	public function addInForDomain(){
		$this->_site->increment('craw_replay',1); 
		$urlRank='http://data.alexa.com/data?cli=10&url='.$this->_site->domain; 
		$getXml=simplexml_load_file($urlRank); 
		if(!empty($getXml->SD->POPULARITY['TEXT'])){
			$this->_site->rank=$getXml->SD->POPULARITY['TEXT']; 
		}
		if(!empty($getXml->SD->COUNTRY['CODE'])){
			$this->_site->country_code=$getXml->SD->COUNTRY['CODE']; 
		}
		if(!empty($getXml->SD->COUNTRY['RANK'])){
			$this->_site->rank_country=$getXml->SD->COUNTRY['RANK']; 
		}  
		try {
			$client = new Client([
				'headers' => [ 
					'Content-Type' => 'text/html',
					'User-Agent' => 'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n'
				], 
				['allow_redirects' => true], 
				'connect_timeout' => '2',
				'timeout' => '2'
			]);
			$url='http://'.$this->_site->domain.'.websiteoutlook.com/'; 
			$response = $client->request('GET', $url); 
			$getResponse=$response->getBody()->getContents(); 
			$dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse; 
			$doc = new \DOMDocument;
			@$doc->loadHTML($dataConvertUtf8, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);    
			$xpath = new \DOMXpath($doc); 
			$nodes = $doc->getElementsByTagName('title');
			//get and display what you need:
			$this->_title = $nodes->item(0)->nodeValue; 
			$metas = $doc->getElementsByTagName('meta');
			for ($i = 0; $i < $metas->length; $i++)
			{
				$meta = $metas->item($i);
				if($meta->getAttribute('name') == 'description')
					$this->_description = $meta->getAttribute('content');
				if($meta->getAttribute('name') == 'keywords')
					$this->_keyword = $meta->getAttribute('content');
			}
			foreach ($xpath->evaluate('//div[@id="basic"]/div[2][contains(concat (" ", normalize-space(@class), " "), " panel-body ")]/div[2]/table[contains(concat (" ", normalize-space(@class), " "), " table table-condensed ")] | //html/body/div[2]/div[2]/div[1]/div[2]/div/div[2]/div[2]/table') as $node) {
				$this->_basicInfo=$doc->saveHtml($node); 
				$this->_basicInfo = preg_replace('/<form.*>[^>]*.*[^>]*>/i','',$this->_basicInfo); 
			}
			foreach ($xpath->evaluate('//div[@id="website"]/div[2][contains(concat (" ", normalize-space(@class), " "), " panel-body ")]/dl[contains(concat (" ", normalize-space(@class), " "), " dl-horizontal ")] | //html/body/div[2]/div[2]/div[1]/div[3]/div/div[2]/dl') as $node) {
				$this->_websiteInfo=$doc->saveHtml($node); 
			}
			foreach ($xpath->evaluate('//div[@id="sem"]/div[2][contains(concat (" ", normalize-space(@class), " "), " panel-body ")] | //html/body/div[2]/div[2]/div[1]/div[4]/div/div[2]') as $node) {
				$this->_semrushMetrics=$doc->saveHtml($node); 
			}
			foreach ($xpath->evaluate('//div[@id="dns"]/div[2][contains(concat (" ", normalize-space(@class), " "), " panel-body ")] | //html/body/div[2]/div[2]/div[1]/div[5]/div/div[2]') as $node) {
				$this->_dnsReport=$doc->saveHtml($node); 
			}
			foreach ($xpath->evaluate('//div[@id="geo"]/div[2][contains(concat (" ", normalize-space(@class), " "), " panel-body ")] | //html/body/div[2]/div[2]/div[1]/div[6]/div/div[2]') as $node) {
				$this->_ipAddressInfo=$doc->saveHtml($node); 
			}
			foreach ($xpath->evaluate('//div[@id="whois"]/div[2][contains(concat (" ", normalize-space(@class), " "), " panel-body ")] | //html/body/div[2]/div[2]/div[1]/div[7]/div/div[2]') as $node) {
				$this->_whoisRecord=$doc->saveHtml($node); 
			}
			$pos = strpos($dataConvertUtf8, 'adsbygoogle'); 
			if ($pos === false) {
				$this->_site->ads='disable';
			}
			$data=array(
				'title'=>$this->_title, 
				'description'=>$this->_description, 
				'keyword'=>$this->_keyword, 
				'basic_info'=>$this->_basicInfo, 
				'website_info'=>$this->_websiteInfo, 
				'semrush_metrics'=>$this->_semrushMetrics, 
				'dns_report'=>$this->_dnsReport, 
				'ip_address_info'=>$this->_ipAddressInfo, 
				'whois_record'=>$this->_whoisRecord
			); 
			if(empty($this->_site->domain_title)){
				$this->_site->domain_title=$this->_title; 
			}
			if(empty($this->_site->domain_description)){
				$this->_site->domain_description=$this->_description; 
			}
			if(empty($this->_site->domain_keywords)){
				$this->_site->domain_keywords=$this->_keyword; 
			}
			$this->_site->content=json_encode($data); 
			$this->_site->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
			$this->_site->status='active';  
			$this->_site->craw_status='active'; 
			$this->_site->craw_by='outlook'; 
			$this->_site->save(); 
			$this->_site=Domain::find($this->_site->id); 
			unset($client);
			return $this->_site;
		}catch (\GuzzleHttp\Exception\ServerException $e){
			$this->_site->save(); 
			$this->_site=Domain::find($this->_site->id); 
			//unset($client);
			return $this->_site;
		}catch (\GuzzleHttp\Exception\BadResponseException $e){
			$this->_site->save(); 
			$this->_site=Domain::find($this->_site->id); 
			//unset($client);
			return $this->_site;
		}catch (\GuzzleHttp\Exception\ClientException $e){
			$this->_site->save(); 
			$this->_site=Domain::find($this->_site->id); 
			//unset($client);
			return $this->_site; 
		}catch (\GuzzleHttp\Exception\ConnectException $e){
			$this->_site->save(); 
			$this->_site=Domain::find($this->_site->id); 
			//unset($client);
			return $this->_site;
		}catch (\GuzzleHttp\Exception\RequestException $e){
			$this->_site->save(); 
			$this->_site=Domain::find($this->_site->id); 
			//unset($client);
			return $this->_site;
		}
		$this->_site->save(); 
		$this->_site=Domain::find($this->_site->id); 
		//unset($client);
		return $this->_site;
	}
	public function makeDir(){
		$dateFolder=[
			'day'=>date('d', strtotime(Carbon::now()->format('Y-m-d H:i:s'))), 
			'month'=>date('m', strtotime(Carbon::now()->format('Y-m-d H:i:s'))), 
			'year'=>date('Y', strtotime(Carbon::now()->format('Y-m-d H:i:s')))
		]; 
		$path = public_path(). '/domaindata/'.$dateFolder['year'].'/'.$dateFolder['month'].'/'.$dateFolder['day'].'/'.$this->_domainAddNewId; 
		$destinationPath = 'domaindata/'.$dateFolder['year'].'/'.$dateFolder['month'].'/'.$dateFolder['day'].'/'.$this->_domainAddNewId; 
		if(!File::exists($path)) {
			File::makeDirectory($path, $mode = 0777, true, true); 
		}
		return $destinationPath; 
	}
	public function homePrice()
    {
		$this->_channel->increment('channel_view',1);
		$return = array();
		return $this->_theme->scope('price', $return)->render();
	}
	public function getFields()
    {
		$fields=Fields::where('name','!=',"")->where('status','=',0)->orderBy('sort_order','desc')->get();
		return response()->json(['success'=>true,
			'message'=>'Danh sách lĩnh vực', 
			'fields'=>$fields
		]);
	}
	
	public function dashboard()
    {
		if($this->_security==true){
			if(isset($this->_channel)){
				$view = array(
					'theme'=>$this->_theme
				);
				return $this->_theme->scope('admin.dashboard', $view)->render();
			}
			else{
				return view('404');
			}
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function getRegionAll(){
		$region=Regions::get(); 
		return response()->json(['success'=>true,
			'message'=>'Danh sách quốc gia',
			'region'=>$region
		]);
    }
	public function getSubRegionByIdRegion(){
		$subRegion=Subregions::where('region_id',$this->_parame['id'])->get(); 
		if(count($subRegion)){
			return response()->json(array('success' => true, 'message'=>'Danh sách khu vực', 'subregion' => $subRegion));
		}
		else{
			return response()->json(array('success'=>false,'message' => 'Không tìm thấy'));
		}
    }
	public function postSubRegionByIdRegion(){
		$idRegion=Input::get('idRegion'); 
		$subRegion=Subregions::where('region_id',$idRegion)->get(); 
		if(count($subRegion)){
			return response()->json(array('success' => true, 'message'=>'Danh sách khu vực', 'subregion' => $subRegion));
		}
		else{
			return response()->json(array('success'=>false,'message' => 'Không tìm thấy'));
		}
    }
	public function postDistrictByIdSubRegion(){
		$idSubRegion=Input::get('idSubRegion'); 
		$district=Region_district::where('subregions_id',$idSubRegion)->get(); 
		if(count($district)){
			return response()->json(array('success' => true, 'message'=>'Danh sách quận huyện', 'district' => $district));
		}
		else{
			return response()->json(array('success'=>false,'message' => 'Không tìm thấy'));
		}
    }
	public function postWardByIdDistrict(){
		$idDistrict=Input::get('idDistrict'); 
		$ward=Region_ward::where('region_district_id',$idDistrict)->get(); 
		if(count($ward)){
			return response()->json(array('success' => true, 'message'=>'Danh sách phường xã', 'ward' => $ward));
		}
		else{
			return response()->json(array('success'=>false,'message' => 'Không tìm thấy'));
		}
    }
	public function contact()
    {
		$lat=0; 
		$lng=0;
		$setAddress=''; 
		if(!empty($this->_channel->joinAddress[0]->address->address)){
			$address=$this->_channel->joinAddress[0]->address->address; 
		}else{
			$address=''; 
		}
		if(!empty($this->_channel->joinAddress[0]->address->joinWard->ward->ward_name)){
			$ward=', '.$this->_channel->joinAddress[0]->address->joinWard->ward->ward_name; 
		}else{
			$ward=''; 
		}
		if(!empty($this->_channel->joinAddress[0]->address->joinDistrict->district->district_name)){
			$district=', '.$this->_channel->joinAddress[0]->address->joinDistrict->district->district_name; 
		}else{
			$district=''; 
		}
		if(!empty($this->_channel->joinAddress[0]->address->joinSubRegion->subregion->subregions_name)){
			$subregion=', '.$this->_channel->joinAddress[0]->address->joinSubRegion->subregion->subregions_name; 
		}else{
			$subregion=''; 
		}
		if(!empty($this->_channel->joinAddress[0]->address->joinRegion->region->country)){
			$region=', '.$this->_channel->joinAddress[0]->address->joinRegion->region->country; 
		}else{
			$region=''; 
		} 
			
		$setAddress=$address.$ward.$district.$subregion.$region; 
		/*if(strlen($setAddress)>5){
			$response = \GoogleMaps::load('geocoding')
			->setParam (['address' =>$setAddress])
			->get(); 
			$jsonDecode=json_decode($response); 
			//dd($jsonDecode); 
			if($jsonDecode->status!='ZERO_RESULTS' && $jsonDecode->status!='OVER_QUERY_LIMIT'){
				$lat=$jsonDecode->results[0]->geometry->location->lat; 
				$lng=$jsonDecode->results[0]->geometry->location->lng; 
			}
		}*/
		$view = array(
			'address'=>$setAddress, 
			'lat'=>$lat, 
			'lng'=>$lng
		);
		return $this->_theme->scope('contact', $view)->render();
	}
	public function contactRequest()
    {
		$name=Input::get('name'); 
		$email=Input::get('email'); 
		$phone=Input::get('phone'); 
		$title=Input::get('title'); 
		$content=Input::get('content'); 
		$messages = array(
			'alpha_dash'=>'Địa chỉ kênh chỉ là dạng chữ không dấu và số',
			'required' => 'Vui lòng nhập thông tin (*).',
			'numeric' => 'Số điện thoại phải dạng số',
			'email' => 'Địa chỉ email không đúng'
		);
		$rules = array(
			'name' => 'required',
			'email'=>'required|email',
			'phone'=>'required',
			'title'=>'required',
			'content'=>'required',
		);
		$validator = Validator::make(Input::all(), $rules, $messages);
		if ($validator->fails())
		{
			return response()->json(['success'=>false,
				'messageType'=>'validation',
				'message'=>$validator->getMessageBag()->toArray(), 
				'msg'=>'Yêu cầu liên hệ của bạn không được gửi đi'
			]);
		}else{
			$contentMessage=[
				'requestFrom'=>(!empty(Auth::user()->id)) ? 'user' : 'ip', 
				'name'=>$name, 
				'email'=>$email, 
				'phone'=>$phone, 
				'title'=>$title, 
				'content'=>$content
			]; 
			$messageInsert=[
				'type'=>'contact', 
				'from'=>(!empty(Auth::user()->id)) ? Auth::user()->id : Request::ip(), 
				'to'=>$this->_channel->id, 
				'message_title'=>'Bạn nhận được 1 liên hệ từ '.$this->_channel->channel_name, 
				'message_body'=>json_encode($contentMessage), 
				'message_status'=>'unread', 
				'message_send'=>'pending', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			]; 
			Messages::create($messageInsert); 
			return response()->json(['success'=>true,
				'message'=>$messageInsert, 
				'msg'=>'Yêu cầu liên hệ của bạn được gửi đến '.$this->_channel->channel_name
			]);
		}
	}
	public function contactConfig()
    {
		if($this->_security==true){
			$view = array();
			return $this->_theme->scope('admin.contact', $view)->render();
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function contactUpdate()
    {
		if($this->_security==true){
			$channelId=Input::get('channelId'); 
			$channelDomain=Input::get('channelDomain'); 
			$channelCompanyName=Input::get('channelCompanyName'); 
			$channelCompanyAddress=Input::get('channelCompanyAddress'); 
			$channelPhone=Input::get('channelPhone'); 
			$channelEmail=Input::get('channelEmail'); 
			$channelRegion=Input::get('channelRegion');
			$channelSubRegion=Input::get('channelSubRegion');
			$messages = array(
				'alpha_dash'=>'Địa chỉ kênh chỉ là dạng chữ không dấu và số',
				'required' => 'Vui lòng nhập thông tin (*).',
				'numeric' => 'Số điện thoại phải dạng số',
				'email' => 'Địa chỉ email không đúng'
			);
			$rules = array(
				'channelCompanyName' => 'required',
				'channelCompanyAddress' => 'required',
				'channelPhone'=>'required|digits_between:10,11',
				'channelEmail'=>'required|email',
				'channelRegion'=>'required',
				'channelSubRegion'=>'required',
			);
			$validator = Validator::make(Input::all(), $rules, $messages);
			if ($validator->fails())
			{
				return response()->json(['success'=>false,
					'messageType'=>'validation',
					'message'=>$validator->getMessageBag()->toArray(),
					'input'=>Input::all()
				]);
			}else{
				/*----Company --*/
				$messageCompany='false'; 
				$getCompany=Company::where('company_name','=',$channelCompanyName)->first(); 
				if(!empty($getCompany->company_name)){
					$getCompany->company_name=$channelCompanyName; 
					$getCompany->company_address=$channelCompanyAddress; 
					$getCompany->company_region=$channelRegion; 
					$getCompany->company_subregion=$channelSubRegion; 
					$getCompany->company_updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
					$getCompany->company_status='pending'; 
					$getCompany->save(); 
					$insert_company_join=[
						'channel_id'=>$this->_channel->id, 
						'company_id'=>$getCompany->id
					];
					Company_join_channel::where('channel_id','=',$this->_channel->id)->delete(); 
					Company_join_channel::insertGetId($insert_company_join); 
				}else{
					$insert_company = [
						'company_name' => $channelCompanyName,
						'company_address' => $channelCompanyAddress,
						'company_region' => $channelRegion,
						'company_subregion' => $channelSubRegion,
						'company_created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'company_updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'company_status'=>'pending'
					];
					$id_company = Company::insertGetId($insert_company); 
					if(isset($id_company)){
						$insert_company_join=[
							'channel_id'=>$this->_channel->id, 
							'company_id'=>$id_company
						];
						Company_join_channel::where('channel_id','=',$this->_channel->id)->delete(); 
						$id_company_join = Company_join_channel::insertGetId($insert_company_join); 
						$insert_company_attribute=[
							'parent_id'=>$id_company, 
							'attribute_type'=>'author', 
							'attribute_value'=>Auth::user()->id, 
							'attribute_status'=>'active', 
							'attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
						];
						Company_attribute::insertGetId($insert_company_attribute); 
					}
				}
				/*----Company End --*/
				/*-- Insert Address */
					if(!empty($this->_channel->joinAddress->address->id)){
						$this->_channel->joinAddress->address->delete(); 
					}
					$idAddress=Address::insertGetId(array(
						'address'=>(!empty($channelCompanyAddress))?$channelCompanyAddress : '', 
						'status'=>'pending', 
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
					)); 
					if($idAddress){
						if(!empty(Regions::find($channelRegion)->id)){
							Address_join_region::insertGetId(array(
								'region_id'=>$channelRegion, 
								'address_id'=>$idAddress
							)); 
						}
						if(!empty(Subregions::find($channelSubRegion)->id)){
							Address_join_subregion::insertGetId(array(
								'subregion_id'=>$channelSubRegion, 
								'address_id'=>$idAddress, 
							));
						}
						if(!empty(Region_district::find($channelDistrict)->id)){
							Address_join_district::insertGetId(array(
								'subregion_id'=>$channelDistrict, 
								'address_id'=>$idAddress, 
							));
						}
						if(!empty(Region_ward::find($channelWard)->id)){
							Address_join_ward::insertGetId(array(
								'subregion_id'=>$channelWard, 
								'address_id'=>$idAddress, 
							));
						}
						Channel_join_address::insertGetId(array(
							'channel_id'=>$this->_channel->id, 
							'address_id'=>$idAddress, 
						)); 
					}
				/*-- End Insert Address */
				/*----Phone --*/
				$getPhone=Phone::where('phone_number','=',$channelPhone)->first(); 
				if(empty($getPhone->phone_number)){
					$insert_phone = [
						'phone_number' => $channelPhone,
						'phone_created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'phone_updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'phone_status'=>'pending'
					];
					$idPhone = Phone::insertGetId($insert_phone); 
				}else{
					$idPhone =$getPhone->id; 
				}
				if($idPhone){
					Channel_join_phone::insertGetId(array(
						'channel_id'=>$this->_channel->id, 
						'phone_id'=>$idPhone
					)); 
				} 
				/*----Phone End--*/
				
				/*----Email --*/
				$getEmail=Email::where('email_address','=',$channelEmail)->first(); 
				if(empty($getEmail->email_address)){
					$insert_email = [
							'email_address' => $channelEmail,
							'email_created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							'email_updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							'email_status'=>'pending'
						];
					$idEmail = Email::insertGetId($insert_email);  
				}else{
					$idEmail =$getEmail->id; 
				}
				if($idEmail){
					Channel_join_email::insertGetId(array(
						'channel_id'=>$this->_channel->id,  
						'email_id'=>$idEmail
					)); 
				}
				/*----Email End --*/
				/*----Channel Join Region --*/
				Channel_join_region::where('channel_id','=',$this->_channel->id)->delete(); 
				Channel_join_region::insertGetId(array( 
					'region_id'=>$channelRegion, 
					'channel_id'=>$this->_channel->id, 
				));
				/*----Channel Join Region End --*/
				/*----Channel Join SubRegion --*/
				Channel_join_subregion::where('channel_id','=',$this->_channel->id)->delete(); 
				Channel_join_subregion::insertGetId(array(
					'subregion_id'=>$channelSubRegion, 
					'channel_id'=>$this->_channel->id, 
				));
				/*----Channel Join SubRegion End --*/
				return response()->json(['success'=>true,
					'messageType'=>'success',
					'url_redirect'=>route('channel.admin.contact'),
					'message'=>'Đã cập nhật thông tin! ', 
				]);
			}
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	
	public function theme()
    {
		if($this->_security==true){
			$view = array();
			return $this->_theme->scope('admin.theme', $view)->render();
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function emailList(){
		$view = array();
		return $this->_theme->scope('admin.tools.email.emailList', $view)->render(); 
	}
	
	
}
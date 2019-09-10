<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Regions;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use App\Permission;
use App\Role; 
use App\Model\Channel_role;
use App\User;
use App\Model\Domain;
use App\Model\Domain_join;
use App\Model\Domain_join_channel;
use App\Model\Domain_attribute;
use App\Model\Services; 
use App\Model\Services_attribute; 
use Carbon\Carbon;
use Pdp\CurlHttpClient;
use Pdp\Manager;
use Pdp\Rules; 
use ParserXml; 
use Theme;
use WebService; 
use Route; 
use Redirect; 
use Response; 
use Input; 
use Validator; 
use Cart; 
use DateTime; 
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use DB;
use Cache;
use App\Http\Controllers\SchedulingController;
class DomainController extends ConstructController
{
	public $_domainRegister;  
	public $_domainRegisterPrimary; 
	public $_domainRegisterLocation; 
	public $_domainRegisterServiceId;  
	public $_domainRegisterStatus; 
	public $_domainRegisterCreatedAt; 
	public $_domainRegisterUpdateAt; 
	public $_domainRegisterDateBegin; 
	public $_domainRegisterDateEnd;
	public $_domainInfo;
	public function __construct(){
		parent::__construct();
	}
	/*-- new --*/
    public function DomainInfo(Request $request){
        $this->_domainInfo=$this->_parame['domain'];
        if(!empty($this->_domainInfo)){
            $parsedUrl=parse_url($request->url());
            if(($parsedUrl['host'] != 'd.cungcap.net') && (config('app.env')!='local')){
                return redirect()->to('https://d.cungcap.net/d/'.$this->_domainInfo,301);
            }
            $domain = Cache::store('memcached')->remember('infoDomain_'.base64_encode($this->_domainInfo), 1, function()
            {
                return DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($this->_domainInfo))->first();
            });
            if(!empty($domain['domain'])){
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($this->_domainInfo))
                    ->increment('view', 1);
                $siteRelate=Cache::store('memcached')->remember('site_relate_'.base64_encode($this->_domainInfo), 1, function() use($domain)
                {
                    return DB::connection('mongodb')->collection('mongo_site')
                        ->where('domain',$domain['domain'])
                        ->limit(10)->get();
                });
                $return=array(
                    'domain'=>$domain,
                    'siteRelate'=>$siteRelate
                );
                return $this->_theme->scope('domain.viewInfo', $return)->render();
            }else{
                $return=array(
                    'domainName'=>$this->_domainInfo
                );
                return $this->_theme->scope('404notfoundDomain', $return)->render();
            }
        }
    }
    public function getDomainTopView(Request $request){
        $page = $request->has('page') ? $request->query('page') : 1;
        $getDomain=Cache::store('memcached')->remember('domain_top_view_'.$page, 1, function()
        {
            return DB::connection('mongodb')->collection('mongo_domain')
                ->orderBy('view','desc')
                ->simplePaginate(20);
        });
        $newDomain=Cache::store('memcached')->remember('newDomain', 1, function()
        {
            return DB::connection('mongodb')->collection('mongo_domain')
                //->where('status','active')
                ->where('craw_next','exists',true)
                ->orderBy('updated_at','desc')
                ->limit(20)->get();
        });
        $keywordNewUpdate=Cache::store('memcached')->remember('newKeyword', 1, function()
        {
            return DB::connection('mongodb')->collection('mongo_keyword')
                //->where('status','active')
                ->where('craw_next','exists',true)
                ->orderBy('updated_at','desc')
                ->limit(20)->get();
        });
        $view = array(
            'getDomain'=>$getDomain,
            'newDomain'=>$newDomain,
            'keywordNewUpdate'=>$keywordNewUpdate
        );
        return $this->_theme->scope('domain.topView', $view)->render();
    }
    public function activeAds(Request $request){
        $getDomain=$request->input('domain');
        if(!empty($getDomain)){
            $domain=DB::connection('mongodb')->collection('mongo_domain')
                ->where('base_64',base64_encode($getDomain))
                ->first();
            if(!empty($domain['domain'])){
                $domainAttribute=$domain['attribute'];
                $noteMer=array('ads'=>'active');
                $domainAttribute= array_merge($domainAttribute, $noteMer);
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('_id',(string)$domain['_id'])
                    ->update(
                        [
                            'attribute'=>$domainAttribute
                        ]
                    );
                return response()->json(['success'=>true,
                    'message'=>'update success',
                ]);
            }else{
                return response()->json(['success'=>false,
                    'message'=>'update faild not found domain',
                ]);
            }
        }else{
            return response()->json(['success'=>false,
                'message'=>'update faild not found domain',
            ]);
        }
    }
    public function disableAds(Request $request){
        $getDomain=$request->input('domain');
        if(!empty($getDomain)){
            $domain=DB::connection('mongodb')->collection('mongo_domain')
                ->where('base_64',base64_encode($getDomain))
                ->first();
            if(!empty($domain['domain'])){
                $domainAttribute=$domain['attribute'];
                $noteMer=array('ads'=>'disable');
                $domainAttribute= array_merge($domainAttribute, $noteMer);
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('_id',(string)$domain['_id'])
                    ->update(
                        [
                            'attribute'=>$domainAttribute
                        ]
                    );
                return response()->json(['success'=>true,
                    'message'=>'update success',
                ]);
            }else{
                return response()->json(['success'=>false,
                    'message'=>'update faild not found domain',
                ]);
            }
        }else{
            return response()->json(['success'=>false,
                'message'=>'update faild not found domain',
            ]);
        }
    }
    public function updateDomainInfo(Request $request){
        $getDomain=$request->input('domain');
        if(!empty($getDomain)){
            $domain=DB::connection('mongodb')->collection('mongo_domain')
                ->where('base_64',base64_encode($getDomain))
                ->first();
            if(!empty($domain['domain'])){
                $newSchedu= new SchedulingController();
                $newSchedu->_domain=$domain['domain'];
                $result=$newSchedu->getInfoSite();
                if($result['result']=='error'){
                    DB::connection('mongodb')->collection('mongo_domain')
                        ->where('base_64',base64_encode($domain['domain']))
                        ->update([
                            'scheme' => $result['scheme'],
                            'status_domain_info'=>$result['result'],
                            'domain_info_status_message'=>$result['message'],
                            'domain_info_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            //'craw_next'=>'whois'
                        ]);
                }else if($result['result']=='active'){
                    $domainAttribute=$domain['attribute'];
                    if(empty($domain['attribute']['ads'])){
                        if($result['data']['status']=='blacklist'){
                            $noteMer=array('ads'=>'disable');
                            $domainAttribute=array_merge($domain['attribute'], $noteMer);
                        }
                    }else if($domain['attribute']['ads']!='disable' || $domain['status']=='blacklist' || $domain['status']=='disable' || $domain['status']=='delete'){
                        if($result['data']['status']=='blacklist'){
                            $noteMer=array('ads'=>'disable');
                            $domainAttribute=array_merge($domain['attribute'], $noteMer);
                        }
                    }
                    DB::connection('mongodb')->collection('mongo_domain')
                        ->where('base_64',base64_encode($domain['domain']))
                        ->update([
                            'scheme' => $result['scheme'],
                            'status_domain_info'=>$result['result'],
                            'domain_info_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            //'craw_next'=>'whois',
                            'title'=>$result['data']['title'],
                            'description'=>$result['data']['description'],
                            'keywords'=>$result['data']['keywords'],
                            'image'=>$result['data']['image'],
                            'get_header'=>$result['data']['get_header'],
                            'contents'=>$result['data']['contents'],
                            'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'attribute'=>$domainAttribute
                        ]);
                }
                return response()->json(['success'=>true,
                    'message'=>'update success',
                ]);
            }else{
                return response()->json(['success'=>false,
                    'message'=>'find notfound domain',
                ]);
            }
        }else{
            return response()->json(['success'=>false,
                'message'=>'domain empty',
            ]);
        }
    }
    public function getDomainByCountryCode(Request $request){
        $page = $request->has('page') ? $request->query('page') : 1;
        $country=Cache::store('memcached')->remember('region_code_'.$this->_parame['iso'], 50, function()
        {
            return DB::table('regions')->where('iso',$this->_parame['iso'])->first();
        });
        $getDomain=Cache::store('memcached')->remember('domain_by_country_'.$this->_parame['iso'].'_'.$page, 1, function()
        {
            return DB::connection('mongodb')->collection('mongo_domain')
                ->where('country',$this->_parame['iso'])
                ->orderBy('updated_at','desc')
                ->simplePaginate(20);
        });
        $newDomain=Cache::store('memcached')->remember('newDomain', 1, function()
        {
            return DB::connection('mongodb')->collection('mongo_domain')
                //->where('status','active')
                ->where('craw_next','exists',true)
                ->orderBy('updated_at','desc')
                ->limit(20)->get();
        });
        $keywordNewUpdate=Cache::store('memcached')->remember('newKeyword', 1, function()
        {
            return DB::connection('mongodb')->collection('mongo_keyword')
                //->where('status','active')
                ->where('craw_next','exists',true)
                ->orderBy('updated_at','desc')
                ->limit(20)->get();
        });
        $view = array(
            'country'=>$country,
            'getDomain'=>$getDomain,
            'newDomain'=>$newDomain,
            'keywordNewUpdate'=>$keywordNewUpdate
        );
        return $this->_theme->scope('domain.listByCountry', $view)->render();
    }
    public function getDomainByIp(Request $request){
        $page = $request->has('page') ? $request->query('page') : 1;
        $getDomain=Cache::store('memcached')->remember('domain_by_country_'.$this->_parame['ip'].'_'.$page, 1, function()
        {
            return DB::connection('mongodb')->collection('mongo_domain')
                ->where('ip',$this->_parame['ip'])
                ->orderBy('updated_at','desc')
                ->simplePaginate(20);
        });
        $newDomain=Cache::store('memcached')->remember('newDomain', 1, function()
        {
            return DB::connection('mongodb')->collection('mongo_domain')
                //->where('status','active')
                ->where('craw_next','exists',true)
                ->orderBy('updated_at','desc')
                ->limit(20)->get();
        });
        $keywordNewUpdate=Cache::store('memcached')->remember('newKeyword', 1, function()
        {
            return DB::connection('mongodb')->collection('mongo_keyword')
                //->where('status','active')
                ->where('craw_next','exists',true)
                ->orderBy('updated_at','desc')
                ->limit(20)->get();
        });
        $view = array(
            'getDomain'=>$getDomain,
            'newDomain'=>$newDomain,
            'ip'=>$this->_parame['ip'],
            'keywordNewUpdate'=>$keywordNewUpdate
        );
        return $this->_theme->scope('domain.listByIp', $view)->render();
    }
		public function inetSignin()
		{
			$client = new Client([
				'headers' => [ 'Content-Type' => 'application/json' ]
			]);
			$postData='{
				"email": "'.config("app.inet_email").'",
				"password": "'.config("app.inet_pass").'"
			}';
			$response = $client->request('POST', 'https://dms.inet.vn/api/sso/v1/user/signin',
				 ['body' => $postData]
			);
			$responseDecode=json_decode($response->getBody()->getContents()); 
			return response()->json(['success'=>true,
				'message'=>'Đăng nhập thành công ', 
				'token'=>$responseDecode->session->token, 
				'status'=>$response->getStatusCode()
			]);
		}
		public function inetDomainCheck()
		{
			
		}
	public function domainList()
    {
		if(Auth::check()){
			$getDomain=Domain::where('user_id','=',Auth::user()->id)->paginate(8); 
			$view = array(
				'getDomain'=>$getDomain
			);
			return $this->_theme->scope('domain.list', $view)->render(); 
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	
	/*--end new*/
	/*
	public function domainList()
    {
		if($this->_security==true){
			$view = array();
			return $this->_theme->scope('admin.domain', $view)->render(); 
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	*/
	public function domainSetPrimary()
	{
		$domainId=Input::get('domainId'); 
		$setDomain=Domain::find($domainId); 
		if(!empty($setDomain->id)){
			foreach($this->_channel->domainAll as $domain){
				$domain->domain->domain_primary='none'; 
				$domain->domain->save(); 
			}
			$setDomain->domain_primary='default'; 
			$setDomain->save(); 
		}
		return response()->json(['success'=>true,
			'message'=>'Cài đặt tên miền chính '.$domainId, 
		]);
	}
	public function domainSaveId()
    {
		if($this->_security==true){
			$domainId=Input::get('domainId'); 
			$date_begin=Input::get('date_begin'); 
			$date_end=Input::get('date_end'); 
			$domainLocation=Input::get('domainLocation'); 
			$domainStatus=Input::get('domainStatus'); 
			$getDomain=Domain::find($domainId); 
			if(!empty($getDomain->domain) && !empty($date_begin) && !empty($date_end) && !empty($domainLocation)){
				$getDomain->date_begin=Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_begin))->format('Y-m-d H:i:s'); 
				$getDomain->date_end=Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_end))->format('Y-m-d H:i:s'); 
				$getDomain->domain_location=$domainLocation; 
				$getDomain->status=$domainStatus;
				$getDomain->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
				$getDomain->save(); 
				return response()->json(['success'=>true,
					'message'=>'Thông tin đã được lưu ', 
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'không tìm thấy tên miền'
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
	public function domainGetId()
    {
		$domainId=Input::get('domainId'); 
		$getDomain=Domain::find($domainId); 
		if(!empty($getDomain->domain)){
			return response()->json(['success'=>true,
				'message'=>'Thông tin tên miền', 
				'getDomain'=>$getDomain, 
				'domain'=>$getDomain->domain, 
				'date_begin'=>Carbon::parse($getDomain->date_begin)->format('d-m-Y'), 
				'date_end'=>Carbon::parse($getDomain->date_end)->format('d-m-Y'), 
			]);
		}else{
			return response()->json(['success'=>false,
				'message'=>'không tìm thấy tên miền'
			]);
		}
	}
	public function domainCheck()
    {
		$domain=Input::get('domain'); 
		$ltdDomain=Input::get('ltdDomain'); 
		$domainType=Input::get('domainType');
		$checkType=Input::get('checkType'); 
		if(!empty($domain)){
			if(empty($ltdDomain)){
				$ltdDomain='com';
			}
			if(WebService::is_valid_url($domain)){
				$domainName=$this->_rulesDomain->resolve($domain);
				$fixDomain=explode('.',$domainName->getRegistrableDomain());
				$domain=$fixDomain[0]; 
			}
//			$pslManager = new PublicSuffixListManager();
//			$parser = new Parser($pslManager->getList());
			if($domainType=='local'){
				$domainFull=Str::slug($domain).'.'.$this->_domain->domain; 
				$channelCheck=Domain::where('domain','=',$domainFull)->get(); 
				if(count($channelCheck) > 0){
					$domainInfo=array(
						'channel'=>array(
							'status'=>'Unavailable', 
							'title'=>$domainFull
						),
					); 
					return response()->json(['success'=>true,
						'message'=>'Thông tin tên miền', 
						'domain'=>$domainFull, 
						'domainType'=>$domainType, 
						'domainInfo'=>$domainInfo, 
						'serviceAttribute'=>Services::find(3)->attribute->where('attribute_type','=','domain')->first(), 
					]);
				}else{
					$domainInfo=array(
						'channel'=>array(
							'status'=>'Available', 
							'title'=>$domainFull
						),
					); 
					return response()->json(['success'=>true,
						'message'=>'Thông tin tên miền', 
						'domain'=>$domainFull, 
						'domainType'=>$domainType, 
						'domainInfo'=>$domainInfo, 
						'serviceAttribute'=>Services::find(3)->attribute->where('attribute_type','=','domain')->first(), 
					]);
				}
			}else{
				$domainName = $this->_rulesDomain->resolve(Str::slug($domain).'.'.$ltdDomain);
				$client = new Client([
					'headers' => [ 'Content-Type' => 'application/json' ]
				]);
				$postData='{
					"domainName": "'.$domainName->getRegistrableDomain().'"
				}';
				$response = $client->request('POST', 'https://dms.inet.vn/api/public/whois/v1/whois/directly',
					 ['body' => $postData]
				);
				$responseDecode=json_decode($response->getBody()->getContents());
				//$service=Services::where('id','=',4)->first();
				return response()->json(['success'=>true,
					'message'=>'Thông tin tên miền', 
					'domain'=>$domainName->getRegistrableDomain(),
					'domainType'=>$domainType, 
					'domainInfo'=>$responseDecode, 
					'serviceAttribute'=>Services::find(4)->attribute->where('attribute_type','=',$domainName->getPublicSuffix())->first(),
				]);
			}
		}else{
			return response()->json(['success'=>false,
				'message'=>'Vui lòng nhập tên miền để kiểm tra',
			]);
		}
	}
	public function addDomain()
    {
		$idDomain=Domain::insertGetId(array(
			'domain'=>$this->_domainRegister, 
			'domain_encode'=>base64_encode($this->_domainRegister), 
			'domain_primary'=>$this->_domainRegisterPrimary, 
			'domain_location'=>$this->_domainRegisterLocation, 
			'service_attribute_id'=>$this->_domainRegisterServiceId, 
			'status'=>$this->_domainRegisterStatus, 
			'created_at'=>$this->_domainRegisterCreatedAt, 
			'updated_at'=>$this->_domainRegisterUpdateAt, 
			'date_begin'=>$this->_domainRegisterDateBegin, 
			'date_end'=>$this->_domainRegisterDateEnd
		)); 
		return $idDomain; 
	}
	public function addNewDomain()
    {
		$Domains=Input::get('Domains'); 
		$listDomain=json_decode($Domains,true); 
		if(count($listDomain)<=0){
			$error='Bạn chưa chọn tên miền nào để thêm! '; 
		}
		if(empty($error)){
			//$pslManager = new PublicSuffixListManager(); 
			//$parser = new Parser($pslManager->getList());
			foreach($listDomain as $domain){
				$domainName=$this->_rulesDomain->resolve($domain['name']); 
				if(!empty($domainName->getRegistrableDomain())){
					$checkDomain=Domain::where('domain_encode','=',base64_encode($domainName->getRegistrableDomain()))->first(); 
					if(empty($checkDomain->domain)){
						if($domain['type']=='local'){
							$this->_domainRegister=$domainName->getRegistrableDomain();  
							$this->_domainRegisterPrimary='default'; 
							$this->_domainRegisterLocation=$domain['type']; 
							$this->_domainRegisterServiceId=2;  
							$this->_domainRegisterStatus='active'; 
							$this->_domainRegisterCreatedAt= Carbon::now()->format('Y-m-d H:i:s'); 
							$this->_domainRegisterUpdateAt=Carbon::now()->format('Y-m-d H:i:s'); 
							$this->_domainRegisterDateBegin=Carbon::now()->format('Y-m-d H:i:s'); 
							$this->_domainRegisterDateEnd=Carbon::now()->format('Y-m-d H:i:s'); 
							$idDomain=$this->addDomain(); 
						}else{
							if(!empty(Services::find(4)->attribute->where('attribute_type','=',$domainName->getPublicSuffix())->first()->id)){
								$getServiceId=Services::find(4)->attribute->where('attribute_type','=',$domainName->getPublicSuffix())->first()->id; 
							}else{
								$getServiceId=''; 
							}
							$this->_domainRegister=$domainName->getRegistrableDomain();  
							$this->_domainRegisterPrimary='none'; 
							$this->_domainRegisterLocation=$domain['type']; 
							$this->_domainRegisterServiceId=$getServiceId;  
							$this->_domainRegisterStatus='pending'; 
							$this->_domainRegisterCreatedAt= Carbon::now()->format('Y-m-d H:i:s'); 
							$this->_domainRegisterUpdateAt=Carbon::now()->format('Y-m-d H:i:s'); 
							$this->_domainRegisterDateBegin=Carbon::now()->format('Y-m-d H:i:s'); 
							$this->_domainRegisterDateEnd=Carbon::now()->format('Y-m-d H:i:s'); 
							$idDomain=$this->addDomain(); 
						}
						if($idDomain){
							Domain_attribute::insertGetId(array(
								'parent_id'=>$idDomain, 
								'attribute_type'=>'author', 
								'attribute_value'=>Auth::user()->id, 
								'attribute_status'=>'active', 
								'attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
							)); 
							Domain_join_channel::insertGetId(array(
								'channel_id'=>$this->_channel->id, 
								'domain_id'=>$idDomain, 
							)); 
							return response()->json(['success'=>true, 
								'message'=>'Đã thêm tên miền vào danh sách.', 
								'domains'=>$Domains
							]);
						}
					}else{
						if(!empty($checkDomain->attributeAuthor->attribute_value) && $checkDomain->attributeAuthor->attribute_value==Auth::user()->id){
							$checkDomain->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
							$checkDomain->save(); 
							Domain_join_channel::where('domain_id','=',$checkDomain->id)->delete(); 
							Domain_attribute::where('parent_id','=',$checkDomain->id)->where('attribute_type','=','author')->delete(); 
							Domain_join_channel::insertGetId(array(
								'channel_id'=>$this->_channel->id, 
								'domain_id'=>$checkDomain->id, 
							)); 
							Domain_attribute::insertGetId(array(
								'parent_id'=>$checkDomain->id, 
								'attribute_type'=>'author', 
								'attribute_value'=>Auth::user()->id, 
								'attribute_status'=>'active', 
								'attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
							)); 
						}else{
							return response()->json(['success'=>true, 
								'message'=>'Tên miền này không thuộc quyền sở hữu của bạn, nên không thể thêm vào! ', 
								'domains'=>$Domains
							]);
						}
					}
				}
			}
		}else{
			return response()->json(['success'=>false, 
				'message'=>$error, 
				'domains'=>$Domains
			]);
		}
	}
	public function domainAddToChannel()
    {
		if($this->_security==true){
			$domain=Input::get('domain'); 
			$domainName=$this->_rulesDomain->resolve($domain); 
			if(!empty($domainName->getRegistrableDomain())){
				$checkDomain=Domain::where('domain_encode','=',base64_encode($domainName->getRegistrableDomain()))->first(); 
				$error=''; 
				$insert='true'; 
				if(!empty($checkDomain->domain)){ 
					if($checkDomain->status=='delete'){
						$checkDomain->status='pending'; 
						$checkDomain->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
						$checkDomain->save(); 
						Domain_join_channel::where('domain_id','=',$checkDomain->id)->delete(); 
						Domain_join_channel::insertGetId(array(
							'domain_id'=>$checkDomain->id, 
							'channel_id'=>$this->_channel->id
						)); 
						Domain_attribute::where('parent_id','=',$checkDomain->id)
							->where('attribute_type','=','author')
							->update(array(
								'attribute_value'=>Auth::user()->id, 
								'attribute_updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
							)); 
						$insert='false'; 
					}else{
						$error='Tên miền này đã tồn tại! ';
					} 
				}
				if(empty($error)){
					if($insert=='true'){
						if(!empty(Services::find(4)->attribute->where('attribute_type','=',$domainName->getPublicSuffix())->first()->id)){
							$getServiceId=Services::find(4)->attribute->where('attribute_type','=',$domainName->getPublicSuffix())->first()->id; 
						}else{
							$getServiceId=''; 
						}
						$this->_domainRegister=$domainName->getRegistrableDomain();  
						$this->_domainRegisterPrimary='none'; 
						$this->_domainRegisterLocation='outside'; 
						$this->_domainRegisterServiceId=$getServiceId;  
						$this->_domainRegisterStatus='pending'; 
						$this->_domainRegisterCreatedAt=Carbon::now()->format('Y-m-d H:i:s'); 
						$this->_domainRegisterUpdateAt=Carbon::now()->format('Y-m-d H:i:s'); 
						$this->_domainRegisterDateBegin=Carbon::now()->format('Y-m-d H:i:s'); 
						$this->_domainRegisterDateEnd=Carbon::now()->format('Y-m-d H:i:s'); 
						$idDomain=$this->addDomain(); 
						if($idDomain){
							Domain_join_channel::insertGetId(array(
								'channel_id'=>$this->_channel->id, 
								'domain_id'=>$idDomain
							)); 
							Domain_attribute::insertGetId(array(
								'parent_id'=>$idDomain, 
								'attribute_type'=>'author', 
								'attribute_value'=>Auth::user()->id, 
								'attribute_status'=>'active', 
								'attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
							)); 
						}
					}
					return response()->json(['success'=>true,
						'message'=>'Tên miền đã được thêm', 
						'domainStatus'=>gethostbyname($domain)
					]);
				}else{
					return response()->json(['success'=>false,
						'message'=>$error, 
					]);
				}
			}else{
				return response()->json(['success'=>false,
					'message'=>'Tên miền không chính xác', 
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
	public function domainDelete()
    {
		if($this->_security==true){
			$domainId=Input::get('domainId'); 
			Domain::where('id','=',$domainId)
				->where('domain_location','=','outside')
				->update(array(
					'status'=>'delete', 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			));
			Domain_join_channel::where('domain_id','=',$domainId)->where('channel_id','=',$this->_channel->id)->delete(); 
			return response()->json(['success'=>true,
				'message'=>'Đã xóa tên miền! ', 
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
}
<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Request;
use Illuminate\Support\Str;
use Auth;
use App\Permission;
use App\Role;
use App\User;
use App\Model\Users_join; 
use App\Model\Users_attribute;  
use App\Model\Email;
use App\Model\Email_join;
use App\Model\Phone;
use App\Model\Phone_join; 
use App\Model\Fields;
use App\Model\Slug; 
use App\Model\Channel; 
use App\Model\Channel_join; 
use App\Model\Channel_join_region;
use App\Model\Channel_join_subregion;
use App\Model\Channel_join_district;
use App\Model\Channel_join_ward;
use App\Model\Channel_join_field;
use App\Model\Channel_role;
use App\Model\Channel_attribute; 
use App\Model\Channel_join_address; 
use App\Model\Channel_join_email; 
use App\Model\Channel_join_phone; 
use App\Model\Category; 
use App\Model\Category_join_channel; 
use App\Model\Address;
use App\Model\Address_join_region;
use App\Model\Address_join_subregion;
use App\Model\Address_join_district;
use App\Model\Address_join_ward;
use App\Model\Domain;
use App\Model\Domain_join; 
use App\Model\Domain_join_channel;
use App\Model\Domain_attribute; 
use App\Model\Services; 
use App\Model\Services_attribute; 
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use Carbon\Carbon;
use App\Model\Region_district;
use App\Model\Region_ward;
use App\Model\Regions;
use App\Model\Subregions;
use App\Model\History; 
use App\Model\Pay_history; 
use App\Model\Pay_history_join_channel; 
use App\Model\Pay_history_join_user; 
use App\Model\Posts; 
use App\Model\Posts_join_channel; 
use App\Model\Media; 
use App\Model\Media_join; 
use App\Model\Media_join_post; 
use App\Model\Media_join_channel; 
use App\Model\Messages;
use App\Model\Order; 
use App\Model\Order_join_channel;
use App\Model\Order_join_user;
use File;
use Youtube; 
use App\Model\Voucher; 
use App\Model\Voucher_attribute; 
use App\Model\Voucher_join; 
use Theme;
use WebService; 
use Route; 
use Redirect; 
use Response; 
use Input; 
use Validator; 
use Hash;
use Cart; 
use Session; 
use Cache; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\DomainController;
class ChannelController extends ConstructController
{

	public $_addChannelDomain; 
	public $_addChannelDomainLtd; 
	public $_addChannelName; 
	public $_addChannelDescription; 
	public $_addChannelEmail; 
	public $_addChannelPhone; 
	public $_addChannelField; 
	public $_addChannelAddress; 
	public $_addChannelRegion; 
	public $_addChannelSubRegion; 
	public $_addChannelDistrict; 
	public $_addChannelWard; 
	public $_addChannelServiceId; 
	public $_addChannelParentId; 
	public $_addChannelAuthor; 
	public $_addChannelDateEnd; 
	public $_addChannelStatus; 
	public function __construct(){
		parent::__construct(); 
	}
	/*--new 19-02-2018--*/
	public function selectPackge(){
		$packge=Input::get('selectPackge'); 
		$serviceAttribute=Services_attribute::find($packge); 
		if(!empty($serviceAttribute->id)){
			Session::put('channelPackge',$packge); 
			return response()->json(['success'=>true,
				'message'=>'Chọn gói cài đặt thành công! '
			]);
		}else{
			return response()->json(['success'=>false,
				'message'=>'Không tìm thấy gói cài đặt! '
			]);
		}
	}
	public function channelAddStep1(){
		/*return response()->json(['success'=>false,
			'message'=>'Hệ thống đang nâng cấp, vui lòng quay lại sau! '
		]);*/
		$domain=Input::get('channelDomain'); 
		$channelName=Input::get('channelName'); 
		$channelDescription=Input::get('channelDescription'); 
		$createDomain=str_replace('-', '',Str::slug($domain)); 
		if(strlen($createDomain)>30){
            return response()->json(['success'=>false,
                'message'=>'Địa chỉ tên miền của bạn quá dài, vui lòng nhập dưới 30 ký tự!  '
            ]);
		}
		if(!empty($createDomain)){
			$checkDomain=Domain::where('domain','=',$createDomain.'.'.$this->_domainPrimary)->first();
			if(!empty($checkDomain->domain)){
				return response()->json(['success'=>false,
					'message'=>$createDomain.'.'.$this->_domainPrimary.' đã có người sử dụng! '
				]);
			}
		}
		if(strlen($channelName)<5){
			return response()->json(['success'=>false,
				'message'=>'Vui lòng nhập tên website! '
			]);
		}
		if(strlen($channelDescription)<5){
			return response()->json(['success'=>false,
				'message'=>'Vui lòng nhập mô tả về website, cửa hàng của bạn! '
			]);
		}
		Session::put('channelDomain',$createDomain);
		Session::put('channelInfo', [ 
			'channelName' => $channelName, 
			'channelDescription' => $channelDescription
		]);
		return response()->json(['success'=>true,
			'message'=>'Cập nhật thông tin thành công! '
		]);
	}
	public function channelAddStep2(){
		$channelFields=Input::get('channelFields'); 
		$channelAddress=Input::get('channelAddress'); 
		$channelRegion=Input::get('channelRegion'); 
		$channelSubRegion=Input::get('channelSubRegion'); 
		$channelDistrict=Input::get('channelDistrict'); 
		$channelWard=Input::get('channelWard'); 
		$fields=explode(',',$channelFields); 
		if(count($fields)<=0){
			return response()->json(['success'=>false,
				'message'=>'Vui lòng chọn ít nhất 1 lĩnh vực hoạt động! '
			]);
		}
		if(strlen($channelAddress)<=2){
			return response()->json(['success'=>false,
				'message'=>'Vui lòng nhập địa chỉ cửa hàng của bạn! '
			]);
		}
		Session::put('channelRegion', [ 
			'channelFields' => $channelFields, 
			'channelAddress' => $channelAddress, 
			'channelRegion' => $channelRegion, 
			'channelSubRegion' => $channelSubRegion, 
			'channelDistrict' => $channelDistrict, 
			'channelWard' => $channelWard, 
		]); 
		return response()->json(['success'=>true,
			'message'=>'Cập nhật thông tin thành công! '
		]);
	}
	public function channelAddStep3(){
		$channelFullName=Input::get('channelFullName'); 
		$channelPhone=Input::get('channelPhone'); 
		$channelEmail=Input::get('channelEmail'); 
		$channelPassword=Input::get('password'); 
		$channelRePassword=Input::get('password_confirmation'); 
		if(strlen($channelPhone)<=9){
			return response()->json(['success'=>false,
				'message'=>'Vui lòng nhập số điện thoại của bạn! '
			]);
		}
		if(strlen($channelEmail)<=5){
			return response()->json(['success'=>false,
				'message'=>'Vui lòng nhập địa chỉ email của bạn! '
			]);
		}
		if(Auth::check()){
			Session::put('channelContact', [ 
				'channelPhone' => $channelPhone, 
				'channelEmail' => $channelEmail, 
			]); 
		}else{
			$getUserByPhone=User::where('phone','=',$channelPhone)->first(); 
			$getUserByEmail=User::where('email','=',$channelEmail)->first(); 
			if(!empty($getUserByPhone->id) && !empty($channelPhone)){
				return response()->json(['success'=>false,
					'message'=>'Số điện thoại này đã có người sử dụng. Nếu đây là tài khoản của bạn, vui lòng đăng nhập và thử lại! '
				]); 
				$error=false; 
			}
			else if(!empty($getUserByEmail->id) && !empty($channelEmail)){
				return response()->json(['success'=>false,
					'message'=>'Email này đã có người sử dụng. Nếu đây là tài khoản của bạn, vui lòng đăng nhập và thử lại! '
				]); 
				$error=false; 
			}else{
				$error=true; 
			}
			if($error==true){
				if(Session::has('channelRegion')){
					$channelRegion=Session::get('channelRegion'); 
				}
				$messages = array(
					'alpha_dash'=>'Địa chỉ kênh chỉ là dạng chữ không dấu và số',
					'required' => 'Vui lòng nhập thông tin (*).',
					'numeric' => 'Dữ liệu phải dạng số',
					'email' => 'Địa chỉ email không đúng', 
					'confirmed'=>'Nhập lại mật khẩu không chính xác'
				);
				$rules = array(
					'channelFullName'=>'required',
					'channelPhone'=>'required|numeric',
					'channelEmail'=>'required|email',
					'password'=>'required|min:6|confirmed',
					'password_confirmation'=>'required|same:password',
				);
				$validator = Validator::make(Input::all(), $rules, $messages);
				if ($validator->fails())
				{
					return response()->json(['success'=>false,
						'message'=>' Không thể tạo tài khoản hoặc mật khẩu nhập lại không chính xác! ', 
					]); 
				}else{
					$userControl= new UserController(); 
					$userControl->_fullName=$channelFullName; 
					$userControl->_phone=$channelPhone; 
					$userControl->_email=$channelEmail; 
					$userControl->_password=$channelPassword; 
					$userControl->_repassword=$channelRePassword; 
					$userControl->_channel=$this->_channel; 
					if(!empty($channelRegion['channelRegion'])){
						$userControl->_region=$channelRegion['channelRegion']; 
					}else{
						$userControl->_region=$this->_channel->channelJoinRegion->region->id; 
					}
					if(!empty($channelRegion['channelSubRegion'])){
						$userControl->_subRegion=$channelRegion['channelSubRegion']; 
					}else{
						$userControl->_subRegion=$this->_channel->channelJoinSubRegion->subregion->id; 
					}
					$result=$userControl->addUser(); 
					if($result!=false){
						Session::put('channelContact', [ 
							'channelFullName' => $channelFullName, 
							'channelPhone' => $channelPhone, 
							'channelEmail' => $channelEmail, 
						]); 
					}else{
						return response()->json(['success'=>false,
							'message'=>'Không thể tạo tài khoản, vui lòng thử lại! '
						]); 
					}
				}
			}
		}
		if(Session::has('channelDomain')){
			$channelDomain=Session::get('channelDomain'); 
			if(Session::has('channelInfo')){
				$channelInfo=Session::get('channelInfo'); 
				if(Session::has('channelContact')){
					$channelContact=Session::get('channelContact'); 
				}
				if(Session::has('channelRegion')){
					$channelRegion=Session::get('channelRegion'); 
				}
                $this->_addChannelDomain=$channelDomain;
                $this->_addChannelDomainLtd=$this->_domainPrimary;
                $this->_addChannelName=$channelInfo['channelName'];
                $this->_addChannelDescription=$channelInfo['channelDescription'];
                $this->_addChannelEmail=$channelContact['channelEmail'];
                $this->_addChannelPhone=$channelContact['channelPhone'];
                $this->_addChannelField=$channelRegion['channelFields'];
                $this->_addChannelAddress=$channelRegion['channelAddress'];
                $this->_addChannelRegion=$channelRegion['channelRegion'];
                $this->_addChannelSubRegion=$channelRegion['channelSubRegion'];
                $this->_addChannelDistrict=$channelRegion['channelDistrict'];
                $this->_addChannelWard=$channelRegion['channelWard'];
                $this->_addChannelServiceId=1;
                $this->_addChannelParentId=$this->_channel->id;
                $this->_addChannelAuthor=Auth::user()->id;
                $this->_addChannelStatus='active';
                $this->_addChannelDateEnd=Carbon::parse(Carbon::now()->format('Y-m-d H:i:s'))->addDays(15)->format('Y-m-d H:i:s');
                $result=$this->addChannel();
                if($result=='success'){
                    return response()->json(['success'=>true,
                        'message'=>'Tạo website thành công! ',
                        'type'=>'free'
                    ]);
                }else{
                    return response()->json(['success'=>false,
                        'message'=>$result,
                        'type'=>'free'
                    ]);
                }
			}else{
				return response()->json(['success'=>false, 
					'message'=>'Không tìm thấy thông tin cài đặt website! ',
				]);
			}
		}else{
			return response()->json(['success'=>false, 
				'message'=>'Không tìm thấy địa chỉ tên miền website! ',
			]);
		}
	}
	public function addChannel(){
		$error=''; 
		if(Session::has('add_channel')){
			$sessionHistory=Session::get('add_channel'); 
			if(!empty($sessionHistory['created_at'])){
				if(Carbon::parse($sessionHistory['created_at'])->addMinutes(10) > Carbon::now()->format('Y-m-d H:i:s')){
					$error='Mỗi website tạo phải cách nhau 10 phút. Lần tạo gần đây nhất của bạn cách đây '.WebService::time_request($sessionHistory['created_at']);
				}
			}
		}
		if(empty($error)){
			if(Session::has('add_channel')){
				Session::forget('add_channel');
			}
			Session::put('add_channel', [ 
				'ip' => Request::ip(), 
				'created_at' => Carbon::now()->format('Y-m-d H:i:s')
			]); 
			$fields=explode(',',$this->_addChannelField); 
			$idChannel=Channel::insertGetId(array(
				'channel_name'=>$this->_addChannelName, 
				'channel_description'=>$this->_addChannelDescription,
				'channel_parent_id'=>$this->_addChannelParentId, 
				'user_id'=>$this->_addChannelAuthor, 
				'service_attribute_id'=>$this->_addChannelServiceId,
				'channel_status'=>$this->_addChannelStatus, 
				'channel_created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'channel_updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'channel_date_end'=>$this->_addChannelDateEnd, 
			)); 
			if($idChannel){
				Channel_role::insertGetId(array(
					'parent_id'=>$idChannel, 
					'user_id'=>$this->_addChannelAuthor, 
					'role_id'=>1, 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				Channel_attribute::insertGetId(array(
					'channel_parent_id'=>$idChannel, 
					'channel_attribute_type'=>'theme', 
					'channel_attribute_value'=>3, 
					'channel_attribute_status'=>'active', 
					'channel_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				$getEmail=Email::where('email_address','=',$this->_addChannelEmail)->first(); 
				if(empty($getEmail->email_address)){
					$insert_email = [
							'email_address' => $this->_addChannelEmail,
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
						'channel_id'=>$idChannel, 
						'email_id'=>$idEmail
					)); 
				}
				$getPhone=Phone::where('phone_number','=',$this->_addChannelPhone)->first(); 
				if(empty($getPhone->phone_number)){
					$insert_phone = [
						'phone_number' => $this->_addChannelPhone,
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
						'channel_id'=>$idChannel, 
						'phone_id'=>$idPhone
					)); 
				}
				$idAddress=Address::insertGetId(array(
					'address'=>(!empty($this->_addChannelAddress))?$this->_addChannelAddress : '', 
					'status'=>'pending', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				if($idAddress){
					if(!empty(Regions::find($this->_addChannelRegion)->id)){
						Address_join_region::insertGetId(array(
							'region_id'=>$this->_addChannelRegion, 
							'address_id'=>$idAddress
						)); 
					}
					if(!empty(Subregions::find($this->_addChannelSubRegion)->id)){
						Address_join_subregion::insertGetId(array(
							'subregion_id'=>$this->_addChannelSubRegion, 
							'address_id'=>$idAddress, 
						));
					}
					if(!empty(Region_district::find($this->_addChannelDistrict)->id)){
						Address_join_district::insertGetId(array(
							'district_id'=>$this->_addChannelDistrict, 
							'address_id'=>$idAddress, 
						));
					}
					if(!empty(Region_ward::find($this->_addChannelWard)->id)){
						Address_join_ward::insertGetId(array(
							'ward_id'=>$this->_addChannelWard, 
							'address_id'=>$idAddress, 
						));
					}
					Channel_join_address::insertGetId(array(
						'channel_id'=>$idChannel, 
						'address_id'=>$idAddress, 
					)); 
				}
				foreach($fields as $field){
					Channel_join_field::insertGetId(array(
						'field_id'=>$field, 
						'channel_id'=>$idChannel, 
					));
				} 
				$newDomain=new DomainController(); 
				$newDomain->_domainRegister=''; 
				$newDomain->_domainRegisterPrimary='default'; 
				$newDomain->_domainRegisterLocation='local'; 
				$newDomain->_domainRegisterServiceId=2; 
				$newDomain->_domainRegisterStatus='active'; 
				$newDomain->_domainRegisterCreatedAt= Carbon::now()->format('Y-m-d H:i:s'); 
				$newDomain->_domainRegisterUpdateAt=Carbon::now()->format('Y-m-d H:i:s'); 
				$newDomain->_domainRegisterDateBegin=Carbon::now()->format('Y-m-d H:i:s'); 
				$newDomain->_domainRegisterDateEnd=Carbon::now()->format('Y-m-d H:i:s'); 
				$idDomain=$newDomain->addDomain(); 
				if($idDomain){
					Domain_join_channel::insertGetId(array(
						'channel_id'=>$idChannel, 
						'domain_id'=>$idDomain, 
					)); 
					$getChannelParent=Channel::find($this->_addChannelParentId); 
					$getDomainLocal=Domain::find($idDomain); 
					$checkDomain=Domain::where('domain_encode','=',base64_encode($this->_addChannelDomain.'.'.$this->_addChannelDomainLtd))->first(); 
					if(empty($checkDomain->id)){
						$urlDomain=$this->_addChannelDomain.'.'.$this->_addChannelDomainLtd; 
						$getDomainLocal->domain=$urlDomain; 
						$getDomainLocal->domain_encode=base64_encode($urlDomain); 
						$getDomainLocal->save(); 
					}else{
						$urlDomain=$this->_addChannelDomain.$idDomain.'.'.$this->_addChannelDomainLtd; 
						$getDomainLocal->domain=$urlDomain; 
						$getDomainLocal->domain_encode=base64_encode($urlDomain); 
						$getDomainLocal->save(); 
					}
					$getChannel=Channel::find($idChannel); 
					$contentMessage=[
						'channel'=>$getChannel, 
						'domain'=>$getDomainLocal
					];
					$messageInsert=[
						'type'=>'channelAdd', 
						'from'=>$this->_addChannelParentId, 
						'to'=>$this->_addChannelAuthor, 
						'message_title'=>'Tạo website thành công trên '.$getChannelParent->channel_name, 
						'message_body'=>json_encode($contentMessage), 
						'message_status'=>'unread', 
						'message_send'=>'pending', 
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
					]; 
					Messages::create($messageInsert); 
					return 'success';
				}else{
					return 'Không thể tạo domain';
				}
			}else{
				return 'Không thể tạo channel! ';
			}
		}else{
			return $error;
		}
	}
	public function channelMe(){
		if(Auth::check()){
			$getChannel=Channel::where('user_id','=',Auth::user()->id)
				->orderBy('channel_updated_at','desc')
				->where('channel_status','!=','delete')
				->paginate(8); 
			$return = array(
				'getChannel'=>$getChannel
			);
			return $this->_theme->scope('channel.me', $return)->render();
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	/*--End new 19-02-2018--*/
	/*--new--*/
	public function sslActive(){
		if($this->_domain->domain_location!='local'){
			$textConfig='
			server {
				listen 443 ssl;
				server_name www.'.$this->_domainPrimary.';
				ssl_certificate /etc/letsencrypt/live/'.$this->_domainPrimary.'/fullchain.pem;
				ssl_certificate_key /etc/letsencrypt/live/'.$this->_domainPrimary.'/privkey.pem;
				include /usr/local/nginx/snippets/ssl.conf;
				rewrite ^(.*) https://'.$this->_domainPrimary.'$1 permanent;
			}
			server {
				listen 80;
				server_name *.'.$this->_domainPrimary.' '.$this->_domainPrimary.';
				#rewrite ^(.*) https://'.$this->_domainPrimary.'$1 permanent; 
				include /usr/local/nginx/snippets/letsencrypt.conf;
				root /home/nginx/domains/cungcap.net/public;
				include /usr/local/nginx/conf/503include-main.conf;
				location / {
				include /usr/local/nginx/conf/503include-only.conf;
				try_files $uri $uri/ /index.php?q=$uri&$args;
				}
				include /usr/local/nginx/conf/pre-staticfiles-global.conf;
				include /usr/local/nginx/conf/staticfiles.conf;
				include /usr/local/nginx/conf/php.conf;
				include /usr/local/nginx/conf/drop.conf;
				include /usr/local/nginx/conf/vts_server.conf;
			}
			server {
				listen 443 ssl;
				server_name *.'.$this->_domainPrimary.' '.$this->_domainPrimary.';
				ssl_certificate /etc/letsencrypt/live/'.$this->_domainPrimary.'/fullchain.pem;
				ssl_certificate_key /etc/letsencrypt/live/'.$this->_domainPrimary.'/privkey.pem;
				include /usr/local/nginx/snippets/ssl.conf;
				root /home/nginx/domains/cungcap.net/public;
				location / {
				try_files $uri $uri/ /index.php?q=$uri&$args;
				}
				include /usr/local/nginx/conf/pre-staticfiles-global.conf;
				include /usr/local/nginx/conf/staticfiles.conf;
				include /usr/local/nginx/conf/php.conf;
				include /usr/local/nginx/conf/drop.conf;
				include /usr/local/nginx/conf/vts_server.conf;
			}
			'; 
			$runFile='#!/bin/bash
	sudo certbot certonly --agree-tos --email contact@cungcap.net --webroot -w /var/lib/letsencrypt/ -d '.$this->_domainPrimary.' -d www.'.$this->_domainPrimary.' 
	sudo service nginx reload 
	rm /home/nginx/domains/cungcap.net/public/conf.d/run/'.$this->_domainPrimary.'.sh
	'; 
			File::put('conf.d/'.$this->_domainPrimary.'.conf',$textConfig);
			File::put('conf.d/run/'.$this->_domainPrimary.'.sh',$runFile); 
			if(File::exists('conf.d/run/'.$this->_domainPrimary.'.sh')) {
				//File::chmod('conf.d/run/'.$this->_domainPrimary.'.sh'); 
				exec('chmod 0755 /home/nginx/domains/cungcap.net/public/conf.d/run/'. $this->_domainPrimary.'.sh');
				$this->_domain->ssl_active='active'; 
				$this->_domain->save(); 
			}
		}
	}
	public function updateVersion(){
		if($this->_security==true){
			$getChannelAttribute=Channel_attribute::where('channel_attribute_type','=','theme')->where('channel_parent_id','=',$this->_channel->id)->first(); 
			$getChannelAttribute->channel_attribute_value=3; 
			$getChannelAttribute->save(); 
			return response()->json(['success'=>true,
				'message'=>'Cập nhật thành công! '
			]);
		}
	}
	public function reUpdateVersion(){
		if($this->_security==true){
			$getChannelAttribute=Channel_attribute::where('channel_attribute_type','=','theme')->where('channel_parent_id','=',$this->_channel->id)->first(); 
			$getChannelAttribute->channel_attribute_value=1; 
			$getChannelAttribute->save(); 
			return response()->json(['success'=>true,
				'message'=>'Cập nhật thành công! '
			]);
		}
	}
	public function statisticsGet(){
		if($this->_security==true){
			$return=array(); 
			return $this->_theme->scope('statistics', $return)->render(); 
		}
	}
	public function upgradeShow(){
		if($this->_security==true){
			$return=array(); 
			return $this->_theme->scope('upgrade', $return)->render(); 
		}
	}
	public function trashGet(){
		if(Auth::check()){
			if($this->_channel->channel_parent_id==0){
				$getPosts=Posts::where('posts.posts_status','!=','active')
					->where('posts.user_id',Auth::user()->id)
					->orderBy('posts.posts_updated_at','desc')
					->paginate(20); 
					$return = array(
						'theme'=>$this->_theme, 
						'getPosts'=>$getPosts
					);
					return $this->_theme->scope('trash', $return)->render(); 
			}else{
				$getPosts=Posts_join_channel::where('posts_join_channel.channel_id','=',$this->_channel->id)
				->join('posts','posts.id','=','posts_join_channel.posts_id')
				->where('posts.posts_status','!=','active')
				->orderBy('posts.posts_updated_at','desc')
				->select('posts.*')
				->paginate(20); 
				$return = array(
					'theme'=>$this->_theme, 
					'getPosts'=>$getPosts
				);
				return $this->_theme->scope('trash', $return)->render(); 
			}
		}
	}
	public function membersGet(){
		if(Auth::check()){
			$getUsers=User::join('channel_role','channel_role.user_id','=','users.id')->where('channel_role.parent_id','=',$this->_channel->id)
				->groupBy('users.id')
				->select('users.*')
				->orderBy('users.updated_at','desc')
				->paginate(20); 
			$return=array(
				'getUsers'=>$getUsers
			); 
			return $this->_theme->scope('members', $return)->render(); 
		}
	}
	public function domainGet(){
		if(Auth::check()){
			$return=array(); 
			return $this->_theme->scope('domain', $return)->render(); 
		}
	}
	public function themeGet(){
		if(Auth::check()){
			$return=array(); 
			return $this->_theme->scope('theme', $return)->render(); 
		}
	}
	public function channelPackge(){
		$getService=Services::find(2); 
		return response()->json(['success'=>true,
			'data'=>json_encode($getService->attributeAll) 
		]);
	}
	public function nameSave(){
		if($this->_security==true){
			$channelName=Input::get('channelName'); 
			$channelDescription=Input::get('channelDescription'); 
			$channelKeywords=Input::get('channelKeywords'); 
			$this->_channel->channel_name=htmlspecialchars(strip_tags($channelName,""), ENT_COMPAT,'UTF-8', true); 
			$this->_channel->channel_description=htmlspecialchars(strip_tags($channelDescription,"<p><br><b><i><strong>"), ENT_COMPAT,'UTF-8', true); 
			$this->_channel->channel_keywords=htmlspecialchars(strip_tags($channelKeywords,""), ENT_COMPAT,'UTF-8', true); 
			$this->_channel->save(); 
			return response()->json(['success'=>true,
				'message'=>'Cập nhật thành công! '
			]);
		}
	}
	public function phoneSave(){
		if($this->_security==true){
			$idPhone=Input::get('idPhone'); 
			$phone=Input::get('phone'); 
			$getPhone=Phone::find($idPhone); 
			if(!empty($getPhone->phone_number) && $getPhone->phone_number!=$phone){
				$idPhone=Phone::insertGetId(array(
					'phone_number'=>$phone, 
					'phone_status'=>'pending', 
					'phone_created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'phone_updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				if($idPhone){
					Channel_join_phone::insertGetId(array(
						'channel_id'=>$this->_channel->id, 
						'phone_id'=>$idPhone
					));  
				}
				return response()->json(['success'=>true,
					'message'=>'Cập nhật số điện thoại thành công! '
				]);
			}else if(empty($getPhone->id)&& !empty($phone)){
				$idPhone=Phone::insertGetId(array(
					'phone_number'=>$phone, 
					'phone_status'=>'pending', 
					'phone_created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'phone_updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				if($idPhone){
					Channel_join_phone::insertGetId(array(
						'channel_id'=>$this->_channel->id, 
						'phone_id'=>$idPhone
					));  
				}
				return response()->json(['success'=>true,
					'message'=>'Cập nhật số điện thoại thành công! '
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không thể cập nhật số điện thoại! '
				]);
			}
		}
	}
	public function emailSave(){
		if($this->_security==true){
			$idEmail=Input::get('idEmail'); 
			$email=Input::get('email'); 
			$getEmail=Email::find($idEmail); 
			if(!empty($getEmail->email_address) && $getEmail->email_address!=$email){
				$idEmail=Email::insertGetId(array(
					'email_address'=>$email, 
					'email_status'=>'pending', 
					'email_created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'email_updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				if($idEmail){
					Channel_join_email::insertGetId(array(
						'channel_id'=>$this->_channel->id, 
						'email_id'=>$idEmail
					));  
				}
				return response()->json(['success'=>true,
					'message'=>'Cập nhật email thành công! '
				]);
			}else if(empty($getEmail->id)&& !empty($email)){
				$idEmail=Email::insertGetId(array(
					'email_address'=>$email, 
					'email_status'=>'pending', 
					'email_created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'email_updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				if($idEmail){
					Channel_join_email::insertGetId(array(
						'channel_id'=>$this->_channel->id, 
						'email_id'=>$idEmail
					));  
				}
				return response()->json(['success'=>true,
					'message'=>'Cập nhật email thành công! '
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không thể cập nhật email! '
				]);
			}
		}
	}
	public function phoneDelete(){
		if($this->_security==true){
			$phoneId=Input::get('phoneId'); 
			$getPhone=Phone::find($phoneId); 
			if(!empty($getPhone->id)){
				$getChannelPhone=Channel_join_phone::where('phone_id','=',$getPhone->id)->where('channel_id','=',$this->_channel->id)->first(); 
				if(!empty($getChannelPhone->phone_id)){
					$getChannelPhone->delete(); 
					return response()->json(['success'=>true,
						'message'=>'Cập nhật phone thành công! '
					]);
				}else{
					return response()->json(['success'=>false,
						'message'=>'Cập nhật phone không thành công! '
					]);
				}
			}
		}
	}
	public function emailDelete(){
		if($this->_security==true){
			$emailId=Input::get('emailId'); 
			$getEmail=Email::find($emailId); 
			if(!empty($getEmail->id)){
				$getChannelEmail=Channel_join_email::where('email_id','=',$getEmail->id)->where('channel_id','=',$this->_channel->id)->first(); 
				if(!empty($getChannelEmail->email_id)){
					$getChannelEmail->delete(); 
					return response()->json(['success'=>true,
						'message'=>'Cập nhật email thành công! '
					]);
				}else{
					return response()->json(['success'=>false,
						'message'=>'Cập nhật email không thành công! '
					]);
				}
			}
		}
	}
	public function addressDelete(){
		if($this->_security==true){
			$addressId=Input::get('addressId'); 
			$getAddress=Address::find($addressId); 
			if(!empty($getAddress->id)){
				$getChannelJoinAddress=Channel_join_address::where('address_id','=',$getAddress->id)->where('channel_id','=',$this->_channel->id)->get(); 
				if(count($getChannelJoinAddress)>0){
					$getAddress->delete(); 
					return response()->json(['success'=>true,
						'message'=>'Cập nhật địa chỉ thành công! '
					]);
				}else{
					return response()->json(['success'=>false,
						'message'=>'Cập nhật địa chỉ không thành công! '
					]);
				}
			}
		}
	}
	public function addressSave(){
		if($this->_security==true){
			$idAddress=Input::get('idAddress'); 
			$address=Input::get('address'); 
			$region=Input::get('region'); 
			$subRegion=Input::get('subRegion'); 
			$district=Input::get('district'); 
			$ward=Input::get('ward'); 
			$getAddress=Address::find($idAddress); 
			if(!empty($getAddress->id)){
				$getAddress->address=$address; 
				$getAddress->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
				$getAddress->save();
				if(!empty($getAddress->joinRegion->region_id)){
					$getAddress->joinRegion->region_id=$region; 
					$getAddress->joinRegion->save(); 
				}else{
					Address_join_region::insertGetId(array(
						'address_id'=>$getAddress->id, 
						'region_id'=>$region
					)); 
				}
				if(!empty($getAddress->joinSubRegion->subregion_id)){
					$getAddress->joinSubRegion->subregion_id=$subRegion; 
					$getAddress->joinSubRegion->save(); 
				}else{
					Address_join_subregion::insertGetId(array(
						'address_id'=>$getAddress->id, 
						'subregion_id'=>$subRegion
					)); 
				}
				if(!empty($getAddress->joinDistrict->district_id)){
					$getAddress->joinDistrict->district_id=$district; 
					$getAddress->joinDistrict->save(); 
				}else{
					Address_join_district::insertGetId(array(
						'address_id'=>$getAddress->id, 
						'district_id'=>$district
					)); 
				}
				if(!empty($getAddress->joinWard->ward_id)){
					$getAddress->joinWard->ward_id=$ward; 
					$getAddress->joinWard->save();
				}else{
					Address_join_ward::insertGetId(array(
						'address_id'=>$getAddress->id, 
						'ward_id'=>$ward
					)); 
				} 
				return response()->json(['success'=>true,
					'message'=>'Cập nhật địa chỉ thành công! '
				]);
			}else if(empty($getAddress->id)&& !empty($address)){
				$idAddress=Address::insertGetId(array(
					'address'=>$address, 
					'status'=>'pending', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				if($idAddress){
					Channel_join_address::insertGetId(array(
						'channel_id'=>$this->_channel->id, 
						'address_id'=>$idAddress
					)); 
					Address_join_region::insertGetId(array(
						'address_id'=>$idAddress, 
						'region_id'=>$region
					)); 
					Address_join_subregion::insertGetId(array(
						'address_id'=>$idAddress, 
						'subregion_id'=>$subRegion
					)); 
					Address_join_district::insertGetId(array(
						'address_id'=>$idAddress, 
						'district_id'=>$district
					)); 
					Address_join_ward::insertGetId(array(
						'address_id'=>$idAddress, 
						'ward_id'=>$ward
					)); 
					return response()->json(['success'=>true,
						'message'=>'Thêm mới địa chỉ thành công! '
					]);
				}
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không tìm thấy địa chỉ cập nhật'
				]);
			}
		}
	}
	/*--end new--*/
	public function channelViewSlug(){
		$slug=explode('-',$this->_parame['slug']); 
		$getChannel=Channel::find($slug[0]); 
		if(!empty($getChannel->id)){
			$getChannel->increment('channel_view',1);
			$return=array(
				'channelInfo'=>$getChannel
			); 
			return $this->_theme->scope('channel.view', $return)->render();
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('themes.404', $view)->render(); 
		}
	}
	public function channelList()
    {
		//$this->_channel->increment('channel_view',1);
		$getChannel=Channel::where('channel.channel_status','!=','delete')
			->where('channel.service_attribute_id','!=',1)
			//->where('channel.channel_parent_id','!=',0)
			//->where('channel.channel_date_end','>=',Carbon::now()->format('Y-m-d H:i:s'))
			//->groupBy('channel.id')
			->orderBy('channel.channel_updated_at','desc')
			->select('channel.*')
			->paginate(9); 
		$getChannel->setPath(route('channel.list',$this->_domainPrimary));
		$getChannelFree=Channel::where('channel.channel_status','!=','delete')
			->where('channel.service_attribute_id','=',1)
			->where('channel.channel_parent_id','!=',0)
			//->where('channel.channel_date_end','>=',Carbon::now()->format('Y-m-d H:i:s'))
			//->groupBy('channel.id')
			->orderBy('channel.channel_updated_at','desc')
			->select('channel.*')
			->paginate(6); 
		$getChannelFree->setPath(route('channel.list.free',$this->_domainPrimary));
		$return = array(
			'getChannel'=>$getChannel, 
			'getChannelFree'=>$getChannelFree
		);
		return $this->_theme->scope('channel.list', $return)->render();
	}
	public function channelListFree()
    {
		$getChannelFree=Channel::where('channel.channel_status','!=','delete')
			->where('channel.service_attribute_id','=',1)
			->where('channel.channel_parent_id','!=',0)
			//->where('channel.channel_date_end','>=',Carbon::now()->format('Y-m-d H:i:s'))
			//->groupBy('channel.id')
			->orderBy('channel.channel_updated_at','desc')
			->select('channel.*')
			->paginate(6); 
		$getChannelFree->setPath(route('channel.list.free',$this->_domainPrimary));
		$return = array(
			'getChannelFree'=>$getChannelFree
		);
		return $this->_theme->scope('channel.list', $return)->render();
	}
	public function trashList() 
	{
		if($this->_security==true){
			$getPosts=Posts_join_channel::where('posts_join_channel.channel_id','=',$this->_channel->id)
			->join('posts','posts.id','=','posts_join_channel.posts_id')
			->where('posts.posts_status','!=','active')
			->orderBy('posts.posts_updated_at','desc')
			->select('posts.*')
			->paginate(8); 
			$return = array(
				'theme'=>$this->_theme, 
				'getPosts'=>$getPosts
			);
			return $this->_theme->scope('admin.channel.trash', $return)->render();
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function channelAdd()
    {
		/*return Redirect::route('channel.home',$this->_domainPrimary,301);
		$getChannel=Channel::where('channel.channel_status','!=','delete')
			->where('channel.service_attribute_id','!=',1)
			->where('channel.channel_parent_id','!=',0)
			->where('channel.channel_date_end','>=',Carbon::now()->format('Y-m-d H:i:s'))
			->groupBy('channel.id')
			->orderBy('channel.channel_updated_at','desc')
			->select('channel.*')
			->paginate(5); 
		$getChannelFree=Channel::where('channel.channel_status','!=','delete')
			->where('channel.service_attribute_id','=',1)
			->where('channel.channel_parent_id','!=',0)
			//->where('channel.channel_date_end','>=',Carbon::now()->format('Y-m-d H:i:s'))
			->groupBy('channel.id')
			->orderBy('channel.channel_updated_at','desc')
			->select('channel.*')
			->paginate(5);
		$getChannel->setPath(route('channel.list',$this->_domainPrimary)); 
		*/
		$getChannelAll = Cache::store('file')->remember('getChannelAll', 360, function()
		{
			return Channel::where('channel_status','!=','delete')->get(); 
		});
		$getUserAll = Cache::store('file')->remember('getUserAll', 360, function()
		{
			return User::all(); 
		});
		$return = array(
			'getChannelAll'=>$getChannelAll, 
			'getUserAll'=>$getUserAll
		);
		return $this->_theme->scope('channel.add', $return)->render();
	}
	public function channelAddInfo()
    {
		if($this->_channel->channel_parent_id==0){
			if(Auth::check()){
				if(Session::has('packgeSelected')){
					$packgeSelected=Session::get('packgeSelected'); 
					$return = array();
					return $this->_theme->scope('channel.addInfo', $return)->render();
				}else{
					return Redirect::route('channel.add',$this->_domainPrimary);
				}
			}else{
				$view = array();
				return $this->_theme->scope('admin.user.login', $view)->render(); 
			}
		}else{
			return Redirect::route('channel.home',$this->_domain->domain);
		}
	}
	public function createChannel()
    {
		if(request()->ajax()){
			$channelName=Input::get('channelName'); 
			$channelDescription=Input::get('channelDescription'); 
			$channelFields=Input::get('channelFields'); 
			$channelEmail=Input::get('channelEmail'); 
			$channelPhone=Input::get('channelPhone'); 
			$channelAddress=Input::get('channelAddress'); 
			$channelRegion=Input::get('channelRegion'); 
			$channelSubRegion=Input::get('channelSubRegion'); 
			$channelDistrict=Input::get('channelDistrict'); 
			$channelWard=Input::get('channelWard'); 
			Session::put('channelAdd', [ 
				'channelName' => $channelName, 
				'channelDescription' => $channelDescription, 
				'channelFields' => $channelFields, 
				'channelEmail' => $channelEmail, 
				'channelPhone' => $channelPhone, 
				'channelAddress' => $channelAddress, 
				'channelRegion' => $channelRegion, 
				'channelSubRegion' => $channelSubRegion, 
				'channelDistrict' => $channelDistrict, 
				'channelWard' => $channelWard, 
			]);
			return response()->json(['success'=>true,
				'session'=>Session::get('channelAdd')
			]);
		}else{
			return false; 
		}
	}
	public function channelUpdateId(){
		if($this->_security==true){
			$date_begin=Input::get('date_begin'); 
			$date_end=Input::get('date_end'); 
			$channelPackge=Input::get('channelPackge'); 
			$this->_channel->channel_created_at=Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_begin))->format('Y-m-d H:i:s'); 
			$this->_channel->channel_date_end=Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_end))->format('Y-m-d H:i:s'); 
			$this->_channel->service_attribute_id=$channelPackge; 
			$this->_channel->save(); 
			return response()->json(['success'=>true,
				'message'=>'Thông tin đã được lưu ', 
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function setting()
    {
		if($this->_security==true){
			$getField=$this->_channel->fields; 
			$getFieldsAll=Fields::where('name','!=',"")->where('status','=',0)->orderBy('sort_order','desc')->get();
			$Field_id=array(); 
			if(isset($getField)){
				foreach ($getField as $Field) {
					$Field_id[] = $Field->field->id;
				}
			}
			$showFieldChannel=WebService::showMenuFields($getFieldsAll,$Field_id,0,""); 
			$getSeo=Channel_attribute::where('channel_parent_id','=',$this->_channel->id)->where('channel_attribute_type','=','seo')->first(); 
			$view = array(
				'channel_fields'=>$showFieldChannel, 
				'getSeo'=>(!empty($getSeo->channel_attribute_value)) ? json_decode($getSeo->channel_attribute_value) : array()
			);
			return $this->_theme->scope('admin.setting', $view)->render();
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function settingUpdate(Request $request)
    {
		if($this->_security==true){
			$channelId=$this->_channel->id; 
			$channelName=Input::get('channelName'); 
			$channelDescription=Input::get('channelDescription'); 
			$channelField=Input::get('channelField'); 
			$metaTitle=Input::get('metaTitle'); 
			$metaDescription=Input::get('metaDescription'); 
			$dataUpdateChannel=[
				'channel_name'=>$channelName, 
				'channel_description'=>$channelDescription, 
				'channel_updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			];
			$channelUpdate=Channel::where('id','=',$this->_channel->id)
				->update($dataUpdateChannel); 
			$jsonField=json_decode($channelField);
			if(count($jsonField)>0){
				Channel_join_field::where('channel_id','=',$this->_channel->id)->delete();  
				foreach($jsonField as $Field_id){
					Channel_join_field::insertGetId(array(
						'field_id'=>$Field_id, 
						'channel_id'=>$this->_channel->id
					));
				}
			}
			if($channelUpdate){
				$attributeSEO=[
					'metaTitle'=>$metaTitle, 
					'metaDescription'=>$metaDescription
				];
				Channel_attribute::where('channel_parent_id','=',$this->_channel->id)->where('channel_attribute_type','=','seo')->delete(); 
				Channel_attribute::insertGetId(array(
					'channel_parent_id'=>$this->_channel->id, 
					'channel_attribute_type'=>'seo', 
					'channel_attribute_value'=>json_encode($attributeSEO)
				)); 
				return response()->json(['success'=>true, 
					'message'=>'Cập nhật thông tin thành công! ', 
				]);
				//return Redirect::route('channel.admin.setting',$this->_domain->domain)-> with('message','Đã cập nhật');
			}else{
				return response()->json(['success'=>false, 
					'message'=>'Không thể cập nhật! ', 
				]);
				//return Redirect::route('channel.admin.setting',$this->_domain->domain)-> with('message','Không thể cập nhật');
			}
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function profileRemoveUserManager()
    {
		$userId=Input::get('userId'); 
		Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
		->where('channel_attribute_type','=','author')
		->where('channel_attribute_value','=',$userId)
		->delete();   
		return response()->json(['success'=>true,
			'message'=>'Xóa quyền quản lý thành công', 
		]);
	}
	public function profileAddUserManager()
    {
		$userId=Input::get('userId'); 
		Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
		->where('channel_attribute_type','=','author')
		->where('channel_attribute_value','=',$userId)
		->delete();  
		Channel_attribute::insertGetId(array(
			'channel_attribute_type'=>'author', 
			'channel_parent_id'=>$this->_channel->id, 
			'channel_attribute_value'=>$userId, 
			'channel_attribute_status'=>'active', 
			'channel_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
			'channel_attribute_updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
		)); 
		return response()->json(['success'=>true,
			'message'=>'Cấp quyền quản lý thành công', 
		]);
	}
	public function profileJoinChannel()
    {
		/*Users_join::where('user_join_table','=','channel')
		->where('table_parent_id','=',$this->_channel->id)
		->where('user_parent_id','=',Auth::user()->id)
		->delete();  
		*/
		$getChannelRole=Channel_role::where('parent_id','=',$this->_channel->id)->where('user_id','=',Auth::user()->id)->first(); 
		if(!empty($getChannelRole->id)){
			$getChannelRole->role_id=3; 
			$getChannelRole->save(); 	
		}else{
			Channel_role::insertGetId(array(
				'parent_id'=>$this->_channel->id, 
				'user_id'=>Auth::user()->id, 
				'role_id'=>3, 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
		}
		/*Users_join::insertGetId(array(
			'user_join_table'=>'channel', 
			'table_parent_id'=>$this->_channel->id, 
			'user_parent_id'=>Auth::user()->id, 
			'user_join_created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
			'user_join_updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
		)); 
		*/
		return response()->json(['success'=>true,
			'message'=>'Gia nhập thành công', 
		]);
	}
	public function channelAttributeColor()
    {
		if($this->_security==true){
			$dataColor=Input::get('dataColor'); 
			$channelLang=Input::get('channelLang'); 
			if(!empty($channelLang)){
				Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
					->where('channel_attribute_type','=','channel_lang')->delete(); 
				Channel_attribute::insertGetId(array(
					'channel_parent_id'=>$this->_channel->id, 
					'channel_attribute_type'=>'channel_lang', 
					'channel_attribute_value'=>$channelLang, 
					'channel_attribute_status'=>'active', 
					'channel_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
			}
			$channelColor=Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
				->where('channel_attribute_type','=','channel_color')->first(); 
			if(!empty($channelColor->channel_attribute_value)){
				Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
					->where('channel_attribute_type','=','channel_color')->delete(); 
			}
			Channel_attribute::insertGetId(array(
				'channel_parent_id'=>$this->_channel->id, 
				'channel_attribute_type'=>'channel_color', 
				'channel_attribute_value'=>$dataColor, 
				'channel_attribute_status'=>'active', 
				'channel_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
			return response()->json(['success'=>true,
				'message'=>'Đã cập nhật màu sắc', 
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function channelAttributeColorReset()
    {
		if($this->_security==true){
			Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
				->where('channel_attribute_type','=','channel_color')->delete(); 
				
			return response()->json(['success'=>true,
				'message'=>'Đã đặt lại màu sắc', 
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function channelAttributeImageCover()
	{
		$getChannel=Channel::find($this->_parame['id']); 
		$media=array(); 
		foreach($getChannel->channelAttributeBanner as $medias){
			$media[]=$medias->media; 
		}
		return response()->json(['success'=>true,
			'gallery'=>$media
		]);	
	}
	public function channelAttributeMediaBackgroundChangeDelete()
    {
		$gallery=Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
			->where('channel_attribute_type','=','background')->first(); 
		if(!empty($gallery->media->media_url)){
			File::delete($gallery->media->media_path.$gallery->media->media_name); 
			File::delete($gallery->media->media_path.'thumb/'.$gallery->media->media_name);
			File::delete($gallery->media->media_path.'small/'.$gallery->media->media_name);
			File::delete($gallery->media->media_path.'xs/'.$gallery->media->media_name);
			Media::where('id','=',$gallery->media->id)->delete(); 
			$gallery->delete(); 
		}
		return response()->json(['success'=>true,
			'message'=>'Xóa hình nền thành công', 
		]);
	}
	public function channelAttributeMediaBackgroundChange()
    {
		if($this->_security==true){
			$mediaId=Input::get('mediaId'); 
			$getMedia=Media::find($mediaId); 
			if(!empty($getMedia->media_url)){
				$gallery=Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
					->where('channel_attribute_type','=','background')->first(); 
				if(!empty($gallery->media->media_url)){
					File::delete($gallery->media->media_path.$gallery->media->media_name); 
					File::delete($gallery->media->media_path.'thumb/'.$gallery->media->media_name);
					File::delete($gallery->media->media_path.'small/'.$gallery->media->media_name);
					File::delete($gallery->media->media_path.'xs/'.$gallery->media->media_name);
					Media::where('id','=',$gallery->media->id)->delete(); 
					$gallery->delete(); 
				}
				Channel_attribute::insertGetId(array(
					'channel_parent_id'=>$this->_channel->id, 
					'channel_attribute_type'=>'background', 
					'channel_attribute_value'=>$getMedia->id, 
					'channel_attribute_status'=>'active', 
					'channel_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				return response()->json(['success'=>true,
					'message'=>'Đã cập nhật hình nền', 
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không thể cập nhật hình nền', 
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
	public function channelAttributeMediaLogoChange()
    {
		if($this->_security==true){
			$mediaId=Input::get('mediaId'); 
			$getMedia=Media::find($mediaId); 
			if(!empty($getMedia->media_url)){
				$gallery=Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
					->where('channel_attribute_type','=','logo')->first(); 
				if(!empty($gallery->media->media_url)){
					File::delete($gallery->media->media_path.$gallery->media->media_name); 
					File::delete($gallery->media->media_path.'thumb/'.$gallery->media->media_name);
					File::delete($gallery->media->media_path.'small/'.$gallery->media->media_name);
					File::delete($gallery->media->media_path.'xs/'.$gallery->media->media_name);
					Media::where('id','=',$gallery->media->id)->delete(); 
					$gallery->delete(); 
				}
				Channel_attribute::insertGetId(array(
					'channel_parent_id'=>$this->_channel->id, 
					'channel_attribute_type'=>'logo', 
					'channel_attribute_value'=>$getMedia->id, 
					'channel_attribute_status'=>'active', 
					'channel_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				return response()->json(['success'=>true,
					'message'=>'Đã thay đổi logo', 
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không thể cập nhật Logo', 
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
	public function channelAttributeMediaAdd()
    {
		if($this->_security==true){
			$channelId=Input::get('channelId'); 
			$channelAttributeType=Input::get('channelAttributeType'); 
			$channelAttributeValue=Input::get('channelAttributeValue'); 
			$idAttribute=Channel_attribute::insertGetId(array(
				'channel_parent_id'=>$this->_channel->id, 
				'channel_attribute_type'=>$channelAttributeType, 
				'channel_attribute_value'=>$channelAttributeValue, 
				'channel_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
			$gallery=Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
				->where('channel_attribute_type','=',$channelAttributeType)->get(); 
			$media=array(); 
			foreach($gallery as $medias){
				$media[]=$medias->media; 
			}
			return response()->json(['success'=>true,
				'message'=>'Đã thêm hình ảnh', 
				'media'=>$media
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function channelAttributeMediaDelete()
    {
		if($this->_security==true){
			$mediaId=Input::get('mediaId');  
			$channelAttributeType=Input::get('channelAttributeType');  
			$gallery=Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
				->where('channel_attribute_type','=',$channelAttributeType)
				->where('channel_attribute_value','=',$mediaId)->first(); 
			if(!empty($gallery->media->media_url)){
				File::delete($gallery->media->media_path.$gallery->media->media_name); 
				File::delete($gallery->media->media_path.'thumb/'.$gallery->media->media_name);
				File::delete($gallery->media->media_path.'small/'.$gallery->media->media_name);
				File::delete($gallery->media->media_path.'xs/'.$gallery->media->media_name);
				Media::where('id','=',$gallery->media->id)->delete(); 
				Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
					->where('channel_attribute_type','=',$channelAttributeType)
					->where('id','=',$gallery->id)->delete(); 
					
				$getGallery=Channel_attribute::where('channel_parent_id','=',$this->_channel->id)
					->where('channel_attribute_type','=',$channelAttributeType)->get(); 
				$media=array(); 
				foreach($getGallery as $medias){
					$media[]=$medias->media; 
				}
				return response()->json(['success'=>true,
					'message'=>'Đã xóa! '.$gallery->media->media_name, 
					'media'=>$media
				]);	
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không tìm thấy media! ', 
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
}
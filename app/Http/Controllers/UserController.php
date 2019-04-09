<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Request; 
use Illuminate\Support\Str;
use Auth;
use App\User; 
use App\Model\Oauth_identities;  
use App\Model\User_login_redirect; 
use App\Model\Users_join_address; 
use App\Model\Users_join; 
use App\Model\Users_join_email; 
use App\Model\Users_join_phone; 
use App\Model\Users_note; 
use App\Model\Users_attribute;  
use App\Model\Email;
use App\Model\Email_join;
use App\Model\Phone;
use App\Model\Phone_join; 
use App\Model\Address;
use App\Model\Address_join_region;
use App\Model\Address_join_subregion;
use App\Model\Address_join_district;
use App\Model\Address_join_ward;
use App\Permission;
use App\Role; 
use App\Model\Domain; 
use App\Model\Domain_join; 
use App\Model\Channel; 
use App\Model\Channel_role; 
use Carbon\Carbon;
use App\Model\History; 
use App\Model\Messages; 
use App\Model\Media; 
use App\Model\Media_join;
use App\Model\Voucher; 
use App\Model\Voucher_attribute; 
use App\Model\Voucher_join; 
use File;
use Theme;
use WebService; 
use Route; 
use Redirect; 
use Response; 
use Input; 
use Validator; 
use Session;
use Cookie;
use Hash;
use Mail;
use URL;
use Socialize; 
use SocialAuth; 
use SocialNorm\Exceptions\ApplicationRejectedException;
use SocialNorm\Exceptions\InvalidAuthorizationCodeException;
class UserController extends ConstructController
{
	public $_fullName; 
	public $_phone; 
	public $_email; 
	public $_password; 
	public $_repassword; 
	public $_region; 
	public $_subRegion; 
	public $_login; 
	public $_userIdLogin; 
	public $_userEmailLogin; 
	public $_userAvataLogin; 
	public $_tokenLogin; 
	public function __construct(){
		parent::__construct(); 
	}
	public function memberRoleSave()
    {
		if($this->_security==true){
			$userId=Input::get('userId'); 
			$roleId=Input::get('roleId'); 
			Channel_role::where('parent_id','=',$this->_channel->id)->where('user_id','=',$userId)->delete(); 
			Channel_role::insertGetId(array(
				'parent_id'=>$this->_channel->id, 
				'user_id'=>$userId, 
				'role_id'=>$roleId, 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				
			)); 
			return response()->json(['success'=>true,
				'message'=>'Đã cập nhật vai trò thành viên', 
				'userId'=>$userId
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->of('themes.404', $view)->render(); 
		}
	}
	public function memberRoleGet()
    {
		if($this->_security==true){
			$userId=Input::get('userId'); 
			$member=User::find($userId); 
			$roles=Role::get(); 
			$getRole=Channel_role::where('parent_id','=',$this->_channel->id)->where('user_id','=',$userId)->first(); 
			if(!empty($getRole->id)){
				return response()->json(['success'=>true,
					'message'=>'Vai trò thành viên', 
					'getRole'=>$getRole, 
					'roles'=>$roles, 
					'member'=>$member
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'Vai trò thành viên trống', 
					'roles'=>$roles, 
					'member'=>$member
				]);
			}
			
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->of('themes.404', $view)->render(); 
		}
	}
	public function memberList()
    {
		if($this->_security==true){
			$getChannelRole=Channel_role::where('parent_id','=',$this->_channel->id)->orderBy('updated_at','desc')->get(); 
			$view=array(
				'getChannelRole'=>$getChannelRole
			); 
			return $this->_theme->of('themes.admin.members', $view)->render();
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->of('themes.404', $view)->render(); 
		}
	}
	public function changePass()
    {
		$oldPass=Input::get('oldPass'); 
		$newPass=Input::get('newPass'); 
		$user = User::find(Auth::user()->id);
		if(Hash::check($oldPass, $user->getAuthPassword())){
            $user->password = Hash::make($newPass);
            if($user->save()) {
                return response()->json(['success'=>true,
					'message'=>'Đã thay đổi mật khẩu', 
				]);
            }
        }else{
            return response()->json(['success'=>false,
				'message'=>'Mật khẩu cũ không đúng', 
			]);
        }
	}
	public function profileSave()
    {
		$name=Input::get('name'); 
		$phone=Input::get('phone'); 
		$email=Input::get('email'); 
		$address=Input::get('address'); 
		$region=Input::get('region'); 
		$subRegion=Input::get('subRegion'); 
		$district=Input::get('district'); 
		$ward=Input::get('ward'); 
		$error=''; 
		$user=User::find(Auth::user()->id); 
		if(!empty($user->phone) && $user->phone!=$phone){
			$getUserByPhone=User::where('phone','=',$phone)->where('phone','!=',$user->phone)->first(); 
			if(!empty($getUserByPhone->id) && !empty($phone)){
				$error='Số điện thoại đã được sử dụng! '; 
			}
		}
		if(!empty($user->email) && $user->email!=$email){
			$getUserByEmail=User::where('email','=',$email)->where('email','!=',$user->email)->first(); 
			if(!empty($getUserByEmail->id) && !empty($email)){
				$error='Email đã được sử dụng! '; 
			}
		}
		if(empty($error)){
			$user->name=$name; 
			$user->phone=$phone; 
			$user->email=$email; 
			$user->save(); 
			$checkPhone=Phone::where('phone_number','=',$phone)->first(); 
			if(empty($checkPhone->phone_number)){
				Users_join_phone::where('user_id','=',$user->id)->delete(); 
				$idPhone=Phone::insertGetId(array(
					'phone_number'=>$phone, 
					'phone_status'=>'pending', 
					'phone_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				if(!empty($idPhone)){
					Users_join_phone::insertGetId(array(
						'user_id'=>$user->id, 
						'phone_id'=>$idPhone
					)); 
				}
			}
			$checkEmail=Email::where('email_address','=',$email)->first(); 
			if(empty($checkEmail->email_address)){
				Users_join_email::where('user_id','=',$user->id)->delete(); 
				$idEmail=Email::insertGetId(array(
					'email_address'=>$email, 
					'email_status'=>'pending', 
					'email_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				if(!empty($idEmail)){
					Users_join_email::insertGetId(array(
						'user_id'=>$user->id, 
						'email_id'=>$idEmail
					)); 
				}
			}
			if(!empty($user->joinAddress->address->id)){
				$getAddress=Address::find($user->joinAddress->address->id); 
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
				}
			}else{
				$idAddress=Address::insertGetId(array(
					'address'=>$address, 
					'status'=>'pending', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				if($idAddress){
					Users_join_address::insertGetId(array(
						'user_id'=>$user->id, 
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
				}
			}
			return response()->json(['success'=>true,
				'message'=>'Đã cập nhật thông tin', 
			]);
		}else{
			return response()->json(['success'=>false,
				'message'=>$error, 
			]);
		}
	}
	public function profileAvataChange()
    {
		if(Auth::check()){
			$mediaId=Input::get('mediaId'); 
			$userAttribute=Users_attribute::where('parent_id','=',Auth::user()->id)
				->where('attribute_type','=','avata')->first(); 
			if(!empty($userAttribute->media->media_url)){
				File::delete($userAttribute->media->media_path.$userAttribute->media->media_name);
				Media::where('id','=',$userAttribute->media->id)
					->where('member_id','=',Auth::user()->id)
					->delete(); 
				Users_attribute::where('parent_id','=',Auth::user()->id)
					->where('attribute_type','=','avata')->delete(); 
			}
			Users_attribute::insertGetId(array(
				'parent_id'=>Auth::user()->id, 
				'attribute_type'=>'avata', 
				'attribute_value'=>$mediaId, 
				'status'=>'active', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
			return response()->json(['success'=>true,
				'message'=>'Đã thay đổi avata', 
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->of('themes.404', $view)->render(); 
		}
	}
	public function profileBankRequest()
    {
		$dataBank=Input::get('dataBank'); 
		$messages = array(
			'required' => 'Vui lòng nhập thông tin (*).',
		);
		$rules = array(
			'dataBank' => 'required|min:10',
		);
		$validator = Validator::make(Input::all(), $rules, $messages);
		if ($validator->fails())
		{
			$error=$validator->errors()->first(); 
		}
		if(empty($error)){
			if(!empty(Auth::user()->getBank->id)){
				Auth::user()->getBank->attribute_value=$dataBank; 
			}else{
				Users_attribute::insertGetId(array(
					'parent_id'=>Auth::user()->id, 
					'attribute_type'=>'bank', 
					'attribute_value'=>$dataBank, 
					'status'=>'active', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
			}
			return response()->json(['success'=>true,
				'message'=>'Cập nhật thành công'
			]);
		}else{
			return response()->json(['success'=>false,
				'message'=>$error
			]);
		}
	}
	public function profileNoteSave()
    {
		if(Auth::check()){
			$dataInputGroup=Input::get('dataInputGroup'); 
			$reportType=Input::get('reportType'); 
			Users_note::where('type','=',$reportType)->where('user_id','=',Auth::user()->id)->delete(); 
			Users_note::insertGetId(array(
				'user_id'=>Auth::user()->id, 
				'type'=>'note_week', 
				'value'=>$dataInputGroup, 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
			return response()->json(['success'=>true,
				'message'=>'Cập nhật thành công'
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->of('themes.404', $view)->render(); 
		}
	}
	public function profileNoteGet()
    {
		if(Auth::check()){
			$reportType=Input::get('reportType'); 
			switch ($reportType) {
				case 'note_week':
					$textReport= "Báo cáo tuần";
					break;
				case 'note_month':
					$textReport= "Báo cáo quý";
					break;
				case 'note_year':
					$textReport= "Báo cáo năm";
					break;
			}
			$noteWeek=Users_note::where('type','=',$reportType)->where('user_id','=',Auth::user()->id)->first(); 
			if(!empty($noteWeek->id)){
				return response()->json(['success'=>true,
					'message'=>$textReport, 
					'dataNote'=>$noteWeek
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>$textReport.' trống'
				]);
			}
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->of('themes.404', $view)->render(); 
		}
	}
	public function profileNote()
    {
		if(Auth::check()){
			$user=User::find(Auth::user()->id); 
			if(count($user->note)>0){
				return response()->json(['success'=>true,
					'message'=>'Danh sách báo cáo', 
					'notes'=>$user->note
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'Danh sách báo cáo trống'
				]);
			}
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->of('themes.404', $view)->render(); 
		}
	}
	public function profileBank()
    {
		$getBank=array(); 
		if(!empty(Auth::user()->getBank->id)){
			$getBank=json_decode(Auth::user()->getBank->attribute_value); 
		}
		return response()->json(['success'=>true,
			'message'=>'Thông tin tài khoản ngân hàng', 
			'bank'=>$getBank
		]);
	}
	public function profileVoucher()
    {
		if(empty(Auth::user()->userVoucher->id)){
			$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
			$keyRandom=substr(str_shuffle(str_repeat($pool, 5)), 0, 5); 
			$getVoucher=Voucher::where('id','=',1)->where('voucher_type','=','channel')->first(); 
			$idVoucherAttribute=Voucher_attribute::insertGetId(array(
				'parent_id'=>$getVoucher->id, 
				'attribute_type'=>'code', 
				'attribute_value'=>Auth::user()->id.'-'.$keyRandom, 
				'status'=>'active'
			));  
			if($idVoucherAttribute){
				Voucher_attribute::insertGetId(array(
					'parent_id'=>$idVoucherAttribute, 
					'attribute_type'=>'author', 
					'attribute_value'=>Auth::user()->id, 
					'status'=>'active'
				)); 
			}
			$voucherCode=Auth::user()->id.'-'.$keyRandom; 
		}else{
			$voucherCode=Auth::user()->userVoucher->voucherCode->attribute_value; 
		}
		return response()->json(['success'=>true,
			'message'=>'Mã khuyến mãi', 
			'voucherCode'=>$voucherCode
		]);
	}
	public function profileCustomers()
    {
		$customers=array(); 
		foreach(Auth::user()->reseller as $customer){
			$customers[]=array(
				'channelName'=>$customer->channel->channel_name, 
				'channelCompany'=>$customer->channel->companyJoin->company, 
				'channelPhone'=>$customer->channel->phoneJoin->phone, 
				'channelEmail'=>$customer->channel->emailJoin->email, 
				'channelDomain'=>$customer->channel->domainJoinPrimary->domainPrimary
			); 
		}
		return response()->json(['success'=>true,
			'message'=>'Tổng số đơn hàng', 
			'customer'=>$customers
		]);
	}
	public function profileSales()
    {
		return response()->json(['success'=>true,
			'message'=>'Tổng số đơn hàng', 
			'totalOrder'=>count(Auth::user()->reseller)
		]);
	}
	public function profileJoinStaff()
    {
		Auth::user()->roles()->attach(4); 
		return response()->json(['success'=>true,
			'message'=>'Đăng ký thành công', 
		]);
	}
	public function profile()
    {
		if(Auth::check()){
			$view = array();
			return $this->_theme->scope('admin.user.profile', $view)->render(); 
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	public function login()
    {
		$urlRedirect=addslashes(Request::query('urlRedirect')); 
		if(!empty($urlRedirect)){
			Session::put('urlRedirect',$urlRedirect);
		} 
		if (Auth::check()) {
			return redirect()->to('/');
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
    }
	public function socialAuthorize(){ 
		$id=addslashes(Request::query('i')); 
		$urlRedirect=addslashes(Request::query('url')); 
		if(!empty($urlRedirect)){
			$valueUrl=[ 
				'id'=>$id, 
				'url'=>$urlRedirect, 
			]; 
			Session::put('urlRedirect',$valueUrl); 
		}
		return SocialAuth::authorize($this->_parame['provider']);
	}
	public function socialLogin(){
		$login=false; 
		$userId=''; 
		$redirect=false; 
		$token=''; 
		$domainPrimary=''; 
		try {
            SocialAuth::login($this->_parame['provider'], function ($user, $details) {
				$getOauth=Oauth_identities::where('provider_user_id','=',$details->id)->where('provider','=',$this->_parame['provider'])->first(); 
				if(!empty($getOauth->id)){
					$getUser=User::find($getOauth->user_id); 
					if(!empty($getUser->id)){
						$findOauth=Oauth_identities::find($getOauth->id); 
						$findOauth->access_token=$details->access_token; 
						$findOauth->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
						$findOauth->save(); 
						$this->_login=true; 
						$this->_tokenLogin=$details->access_token; 
						$this->_userIdLogin=$getUser->id; 
					}
				}else{
					$getUser=User::where('email','=',$details->email)->first(); 
					if(!empty($getUser->id)){
						Oauth_identities::where('user_id','=',$getUser->id)->delete(); 
						Oauth_identities::insertGetId(array(
							'user_id'=>$getUser->id, 
							'provider_user_id'=>$details->id, 
							'provider'=>$this->_parame['provider'], 
							'access_token'=>$details->access_token, 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
						)); 
						$this->_login=true; 
						$this->_tokenLogin=$details->access_token; 
						$this->_userAvataLogin=$details->avatar; 
						$this->_userIdLogin=$getUser->id; 
					}else{
						$confirmation_code = str_random(30);
						$email=''; 
						$avata=''; 
						$this->_userEmailLogin=$details->email; 
						$userId=User::insertGetId(array(
							'name'=>$details->full_name, 
							'email'=>(!empty($this->_userEmailLogin))? $this->_userEmailLogin : '', 
							'password'=>bcrypt($confirmation_code), 
							'avata'=>(!empty($this->_userAvataLogin))? $this->_userAvataLogin : '', 
							'confirmation_code'=>$confirmation_code, 
							'user_status'=>'pending', 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
						));  
						if(!empty($userId)){
							$user=User::find($userId); 
							$this->_login=true; 
							$this->_tokenLogin=$details->access_token; 
							$this->_userIdLogin=$user->id; 
							Oauth_identities::where('user_id','=',$user->id)->delete(); 
							Oauth_identities::insertGetId(array(
								'user_id'=>$user->id, 
								'provider_user_id'=>$details->id, 
								'provider'=>$this->_parame['provider'], 
								'access_token'=>$details->access_token, 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
							)); 
							$channelId=$this->_channel->id; 
							if(Session::has('urlRedirect')){
								$sessionUrl=Session::get('urlRedirect'); 
								$getChannel=Channel::find($sessionUrl['id']); 
								if(!empty($getChannel->id)){
									$redirect=true; 
									$channelId=$getChannel->id; 
								}
							}
							Channel_role::insertGetId(array(
								'parent_id'=>$channelId, 
								'user_id'=>$user->id, 
								'role_id'=>3, 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
							)); 
							$user->roles()->attach(3); 
							if(!empty($user->email)){
								$email=$user->email; 
								$getEmail=Email::where('email_address','=',$user->email)->first(); 
								if(!empty($getEmail->id)){
									$idEmail=$getEmail->id; 
								}else{
									$idEmail=Email::insertGetId(array(
										'email_address'=>$user->email, 
										'email_status'=>'pending', 
										'email_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
									));
								} 
								if($idEmail){
									Users_join_email::insertGetId(array(
										'user_id'=>$user->id, 
										'email_id'=>$idEmail
									)); 
								}
								$bodyMessage=[
									'user_name'=>$user->name, 
									'user_email'=>$user->email, 
									'user_phone'=>'', 
									'confirmation_code'=>$user->confirmation_code, 
									'user_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
								]; 
								$data_message_to_user = array(
									'type'=>'userRegister', 
									'message_group' => date_timestamp_get(date_create()),
									'from' => $channelId,
									'to' => $user->id,
									'message_title' => 'Đăng ký tài khoản thành công',
									'message_body' => json_encode($bodyMessage), 
									'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
									'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

								); 
								Messages::create($data_message_to_user); 
							}
						}
					}
				}
            }); 
        }catch(ApplicationRejectedException $e){
           $error='Bạn cần cho phép ứng dụng được xem những thông tin cơ bản của bạn cho việc đăng nhập! '; 
        }catch(InvalidAuthorizationCodeException $e) {
            $error='Đăng nhập không thành công! '; 
        } 
		if($this->_login==true){
			if(Session::has('urlRedirect')){
				$sessionUrl=Session::get('urlRedirect'); 
				$getChannel=Channel::find($sessionUrl['id']); 
				if(!empty($getChannel->id)){
					User_login_redirect::where('user_id','=',$this->_userIdLogin)->delete(); 
					User_login_redirect::insertGetId(array(
						'user_id'=>$this->_userIdLogin, 
						'token'=>$this->_tokenLogin
					));
					if($getChannel->domainJoinPrimary->domain->domain_primary!='default'){
						foreach($getChannel->domainAll as $domain){
							if($domain->domain->domain_primary=='default'){
								$domainPrimary=$domain->domain->domain; 
							}
						}
					}else{
						$domainPrimary=$getChannel->domainJoinPrimary->domain->domain; 
					} 
					 return redirect()->route('channel.login.redirect',array($domainPrimary,'i'=>$getChannel->id,'token'=>$this->_tokenLogin,'url'=>urlencode($sessionUrl['url']))); 
				}else{
					 return redirect()->route('channel.home',$domainPrimary);
				}
			}else{
				$getUser=User::find($this->_userIdLogin); 
				Auth::attempt(['id' => $getUser->id, 'password' => $getUser->password]); 
				return redirect()->route('channel.home',$this->_domainPrimary);
			}
		}else{
			return 'Không đăng nhập được! '; 
		}
		//return $error; 
	}
	public function loginRedirect(){
		$id=addslashes(Request::query('i')); 
		$urlRedirect=addslashes(Request::query('url')); 
		$token=Request::query('token'); 
		$getUserRedirect=User_login_redirect::where('token','=',$token)->first(); 
		if(!empty($getUserRedirect->id)){
			if(!empty($getUserRedirect->user->id)){
				$user=Auth::login($getUserRedirect->user, true); 
				//dd($user); 
				//Auth::attempt(['id' => $getUserRedirect->user->id, 'password' => $getUserRedirect->user->password]); 
				User_login_redirect::where('user_id','=',$getUserRedirect->user->id)->delete(); 
				return redirect()->to('http://'.$urlRedirect);
			}else{
				return Redirect::route('channel.home',$this->_domainPrimary);
			}
		}else{
			return Redirect::route('channel.home',$this->_domainPrimary);
		}
	}
	public function loginRequest()
    {
		if(Session::has('urlRedirect')){
			$sessionUrl=Session::get('urlRedirect'); 
		}else{
			$sessionUrl=URL::previous(); 
		}
		if (Auth::attempt(['phone' => Input::get('email'), 'password' => Input::get('password')], true)) {
            Cookie::queue('shared_cookie', Session::getId(), 999999, null, '.'.config('app.url'));
			return response()->json(['success'=>true,
				'message'=>'Đăng nhập thành công! ', 
				'return_url' => $sessionUrl
			]);
		}else if (Auth::attempt(['email'=> Input::get('email'), 'password' => Input::get('password')], true)) {
            Cookie::queue('shared_cookie', Session::getId(), 999999, null, '.'.config('app.url'));
			return response()->json(['success'=>true,
				'message'=>'Đăng nhập thành công! ', 
				'return_url' => $sessionUrl
			]);
		}else{
			return response()->json(['success'=>false,
				'message'=>'Tài khoản hoặc mật khẩu không đúng! ',
			]);
		}
	}
	public function userActiveCode(){
		if(!empty($this->_parame['code'])){
			$getUser=User::where('confirmation_code','=',$this->_parame['code'])->first(); 
			if(!empty($getUser->id)){
				if($getUser->user_status!='active'){
					$getUser->user_status='active'; 
					$getUser->confirmation_code=''; 
					$getUser->save(); 
					$activeStatus='active'; 
					$getEmail=Email::where('email_address','=',$getUser->email)->first(); 
					if(!empty($getEmail->id)){
						$getEmail->email_status='active';
						$getEmail->save(); 
					}
				}else{
					$activeStatus='activated';
				}
			}else{
				$activeStatus='noUser';
			}
		}else{
			$activeStatus='noFindCode';
		}
		$view = array(
			'activeStatus'=>$activeStatus
		);
		return $this->_theme->scope('admin.user.activeCode', $view)->render();
	}
	public function profileResendCode(){
		if(Auth::check()) {
			$getUser=User::find(Auth::user()->id); 
			if($getUser->user_status!='active'){
				$confirmation_code = str_random(30); 
				$getUser->confirmation_code=$confirmation_code; 
				$getUser->save(); 
				$data=[
					'user'=>$getUser, 
					'confirmation_code'=>$confirmation_code, 
					'channel'=>$this->_channel, 
					'message_title'=>'Kích hoạt tài khoản trên '.$this->_channel->channel_name, 
					'date_send'=>Carbon::now()->format('Y-m-d H:i:s'), 
				]; 
				Mail::send('emails.reSendCode', $data, function ($msg) use ($data) {
					$msg->from(config('app.app_email'),$data['channel']->channel_name);
					//$msg->replyTo($data['userEmail'], $data['userName']); 
					$msg->to($data['user']->email, $data['user']->name)
						//->cc(config('app.app_email'))
						->subject($data['message_title']);
				});
				if(count(Mail::failures())>0){
					return response()->json(['success'=>false,
						'message'=>'Không thể gửi email! ',
					]);
				}else{
					return response()->json(['success'=>true,
						'message'=>'Đã gửi email mã kích hoạt ',
					]);
				}
			}else{
				return response()->json(['success'=>false,
					'message'=>'Tài khoản của bạn đã được kích hoạt! ',
				]);
			}
		}
	}
	public function register()
    {
		$urlRedirect=addslashes(Request::query('urlRedirect')); 
		if(!empty($urlRedirect)){
			Session::put('urlRedirect',$urlRedirect);
		} 
		if (Auth::check()) {
			return redirect()->to('/');
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.register', $view)->render();
		}
    }
	public function registerRequest()
    {
		$fullName=Input::get('fullName'); 
		$phone=Input::get('phone'); 
		$email=Input::get('email'); 
		$password=Input::get('password'); 
		$password_confirmation=Input::get('password_confirmation'); 
		$region=Input::get('region'); 
		$subRegion=Input::get('subRegion'); 
		$error = ''; 
		$messages = array(
			'alpha_dash'=>'Địa chỉ kênh chỉ là dạng chữ không dấu và số',
			'required' => 'Vui lòng nhập thông tin (*).',
			'numeric' => 'Dữ liệu phải dạng số',
			'email' => 'Địa chỉ email không đúng', 
			'confirmed'=>'Nhập lại mật khẩu không chính xác'
		);
		$rules = array(
			'fullName' => 'required',
			'phone'=>'required|numeric',
			'email'=>'required|email',
			'password'=>'required|min:6|confirmed',
			'password_confirmation'=>'required|same:password',
		);
		$validator = Validator::make(Input::all(), $rules, $messages);
		if ($validator->fails())
		{
			$error='Lỗi không thể đăng ký tài khoản'; 
			$msg='Thông tin cập nhật thiếu'; 
			$errorType='validation'; 
		}else{ 
			$getUserByPhone=User::where('phone','=',$phone)->first(); 
			$getUserByEmail=User::where('email','=',$email)->first(); 
			if(!empty($getUserByPhone->id) && !empty($phone)){
				$error=$msg='Số điện thoại đã được sử dụng! '; 
				$errorType='default'; 
			}
			else if(!empty($getUserByEmail->id) && !empty($email)){
				$error=$msg='Email đã được sử dụng! '; 
				$errorType='default'; 
			}
		}
		$checkHistory=History::where('author','=',Request::ip())
			->where('history_type','=','register_add')
			->orderBy('created_at','asc')
			->first(); 
		if(!empty($checkHistory->created_at)){
			if(Carbon::parse($checkHistory->created_at)->addMinutes(60) > Carbon::now()->format('Y-m-d H:i:s')){
				$error=$msg='Tạo tài khoản cách nhau 60 phút. Lần tạo gần đây nhất của bạn cách đây '.WebService::time_request($checkHistory->created_at); 
			}
		}
		if(empty($error)){
			$this->_fullName=$fullName; 
			$this->_phone=$phone; 
			$this->_email=$email; 
			$this->_region=$region; 
			$this->_subRegion=$subRegion; 
			$this->_password=$password; 
			$result=$this->addUser(); 
			if($result!=false){
				if(Session::has('urlRedirect')){
					$sessionUrl=Session::get('urlRedirect'); 
				}else{
					$sessionUrl=URL::previous(); 
				}
				return response()->json(['success'=>true,
					'message'=>'Đăng ký thành công', 
					'return_url' => $sessionUrl
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không thể tạo tài khoản, vui lòng thử lại!', 
					'messageType'=>'default'
				]);
			}
		}else{
			return response()->json(['success'=>false,
				'message'=>$error, 
				'msg'=>$msg, 
				'messageType'=>$errorType
			]);
		}
	}
	public function addUser()
    {
		$confirmation_code = str_random(30);
		$user= new User(); 
		$user->name=$this->_fullName; 
		$user->email=$this->_email; 
		$user->phone=$this->_phone; 
		$user->password=bcrypt($this->_password); 
		$user->confirmation_code=$confirmation_code; 
		$user->created_at=Carbon::now()->format('Y-m-d H:i:s'); 
		$user->user_status='pending'; 
		$user->save(); 
		if(!empty($user->id)){
			Channel_role::insertGetId(array(
				'parent_id'=>$this->_channel->id, 
				'user_id'=>$user->id, 
				'role_id'=>3, 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
			$user->roles()->attach(3); 
			if(!empty($this->_region)){
				Users_attribute::where('attribute_type','=','region')->where('parent_id','=',$user->id)->delete(); 
				Users_attribute::insertGetId(array(
					'parent_id'=>$user->id, 
					'attribute_type'=>'region', 
					'attribute_value'=>$this->_region, 
					'status'=>'active', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
			}
			if(!empty($this->_subRegion)){
				Users_attribute::where('attribute_type','=','subRegion')->where('parent_id','=',$user->id)->delete(); 
				Users_attribute::insertGetId(array(
					'parent_id'=>$user->id, 
					'attribute_type'=>'subRegion', 
					'attribute_value'=>$this->_subRegion, 
					'status'=>'active', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
			}
			$getPhone=Phone::where('phone_number','=',$this->_phone)->first(); 
			$getEmail=Email::where('email_address','=',$this->_email)->first(); 
			if(!empty($getPhone->id)){
				$idPhone=$getPhone->id; 
			}else{
				$idPhone=Phone::insertGetId(array(
					'phone_number'=>$this->_phone, 
					'phone_status'=>'pending', 
					'phone_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
			}
			if($idPhone){
				Users_join_phone::insertGetId(array(
					'user_id'=>$user->id, 
					'phone_id'=>$idPhone
				));  
			}
			if(!empty($getEmail->id)){
				$idEmail=$getEmail->id; 
			}else{
				$idEmail=Email::insertGetId(array(
					'email_address'=>$this->_email, 
					'email_status'=>'pending', 
					'email_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				));
			} 
			if($idEmail){
				Users_join_email::insertGetId(array(
					'user_id'=>$user->id, 
					'email_id'=>$idEmail
				)); 
			}
			$authenData = array(
				'id' => $user->id,
				'password' => $this->_password,
			);
			if (Auth::attempt($authenData)) {
				$bodyMessage=[
					'user_name'=>$user->name, 
					'user_email'=>$user->email, 
					'user_phone'=>$user->phone, 
					'confirmation_code'=>$confirmation_code, 
					'user_created_at'=>$user->created_at, 
					'password'=>$this->_password,
				]; 
				$data_message_to_user = array(
					'type'=>'userRegister', 
					'message_group' => date_timestamp_get(date_create()),
					'from' => $this->_channel->id,
					'to' => $user->id,
					'message_title' => 'Đăng ký tài khoản thành công',
					'message_body' => json_encode($bodyMessage), 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

				);
				Messages::create($data_message_to_user); 
				History::insertGetId(array(
					'history_type'=>'register_add', 
					'parent_id'=>$this->_channel->id, 
					'author'=>Request::ip(),
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				)); 
				$getUser=User::find($user->id); 
				return $getUser; 
			}
		}else{
			return false; 
		}
	}
	public function logout()
    {
        Auth::logout();
        Cookie::forget('shared_cookie');
        return redirect()->route('channel.home',$this->_parame['domain']);
    }
}
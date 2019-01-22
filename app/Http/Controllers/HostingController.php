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
use App\Model\Channel_role;
use App\User;
use App\Model\Hosting;
use App\Model\Hosting_join;
use App\Model\Services; 
use App\Model\Services_attribute; 
use Carbon\Carbon;
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
class HostingController extends ConstructController
{
	public function __construct(){
		parent::__construct(); 
	}
	public function hostingSaveId()
    {
		if($this->_security==true){
			$hostingId=Input::get('hostingId'); 
			$date_begin=Input::get('date_begin'); 
			$date_end=Input::get('date_end'); 
			$hostingStatus=Input::get('hostingStatus'); 
			$getHosting=Hosting::find($hostingId); 
			if(!empty($getHosting->name) && !empty($date_begin) && !empty($date_end)){
				$getHosting->date_begin=Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_begin))->format('Y-m-d H:i:s'); 
				$getHosting->date_end=Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_end))->format('Y-m-d H:i:s'); 
				$getHosting->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
				$getHosting->status=$hostingStatus; 
				$getHosting->save(); 
				return response()->json(['success'=>true,
					'message'=>'Thông tin đã được lưu ', 
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'không tìm thấy hosting'
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
	public function hostingGetId()
    {
		$hostingId=Input::get('hostingId'); 
		$getHosting=Hosting::find($hostingId); 
		if(!empty($getHosting->name)){
			return response()->json(['success'=>true,
				'message'=>'Thông tin hosting', 
				'getHosting'=>$getHosting, 
				'date_begin'=>Carbon::parse($getHosting->date_begin)->format('d-m-Y'), 
				'date_end'=>Carbon::parse($getHosting->date_end)->format('d-m-Y'), 
			]);
		}else{
			return response()->json(['success'=>false,
				'message'=>'không tìm thấy hosting'
			]);
		}
	}
	public function hostingRemove(){
		if($this->_security==true){
			$hostingId=Input::get('hostingId'); 
			Hosting::find($hostingId)->delete(); 
			return response()->json(['success'=>true, 
				'message'=>'Đã xóa dịch vụ hosting', 
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function hostingAdd(){
		if($this->_security==true){
			$hostingName=Input::get('hostingName'); 
			$serviceAttributeId=Input::get('serviceAttributeId'); 
			$date_begin=Input::get('date_begin'); 
			$date_end=Input::get('date_end'); 
			$hostingId=Hosting::insertGetId(array(
				'name'=>$hostingName, 
				'type'=>'outsite', 
				'service_attribute_id'=>$serviceAttributeId, 
				'status'=>'active', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'date_begin'=>Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_begin))->format('Y-m-d H:i:s'), 
				'date_end'=>Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_end))->format('Y-m-d H:i:s')
			)); 
			if(isset($hostingId)){
				Hosting_join::insertGetId(array(
					'table'=>'channel', 
					'table_parent_id'=>$this->_channel->id, 
					'hosting_parent_id'=>$hostingId, 
					'status'=>'active', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				return response()->json(['success'=>true, 
					'message'=>'Đã thêm dịch vụ hosting', 
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
	public function changeDomain()
    {
		if(Auth::check()){
			if(!empty($this->_parame['id'])){
				$hosting=Hosting::where('user_id','=',Auth::user()->id)->where('id','=',$this->_parame['id'])->first(); 
				if(!empty($hosting->id)){
					$view = array(
						'hosting'=>$hosting
					);
					return $this->_theme->scope('hosting.changeDomain', $view)->render();
				}else{
					return redirect()->route('hosting.list',$this->_domainPrimary);
				}
			}else{
				return redirect()->route('hosting.list',$this->_domainPrimary);
			} 
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	public function changeDomainSave()
    {
		if(Auth::check()){
			$idHosting=Input::get('idHosting');
			$domainOld=Input::get('domainOld');
			$domainNew=Input::get('domainNew');
			if(!empty($idHosting)){
				if(WebService::is_valid_url($domainNew)){
					$domainNameCheck = $this->_parser->parseUrl($domainNew); 
					if(!is_null($domainNameCheck->host->registerableDomain)){
						$hosting=Hosting::where('user_id','=',Auth::user()->id)->where('id','=',$idHosting)->where('type','=','host_cungcap')->first(); 
						if(!empty($hosting->id)){
							$dataHosting=json_decode($hosting->content); 
							$contentHost=array(
								'domain'=>$domainNameCheck->host->registerableDomain, 
								'userName'=>$dataHosting->userName, 
								'password'=>$dataHosting->password, 
								'url_login'=>'https://host.cungcap.net:2083'
							); 
							$hosting->name=$domainNameCheck->host->registerableDomain; 
							$hosting->content=json_encode($contentHost); 
							$hosting->save(); 
							$cpanel = new \Gufy\CpanelPhp\Cpanel([
								  'host'        =>  'https://host.cungcap.net:2087', 
								  'username'    =>  'root', 
								  'auth_type'   =>  'hash', 
								  'password'    =>  'NE1FIY5EDECUIC1OZRNI0ZGG6JRP04CG',
							]);
							$accounts = $cpanel->modifyAccounts(['api.version'=>1,'user' => $dataHosting->userName,'domain'=>$domainNameCheck->host->registerableDomain]); 
							return response()->json(['success'=>true, 
								'message'=>'Đổi tên miền thành công', 
							]);
						}else{
							return response()->json(['success'=>false, 
								'message'=>'Không tìm thấy gói hosting hoặc gói này không thể cập nhật', 
							]);
						}
					}else{
						return response()->json(['success'=>false, 
							'message'=>'Tên miền không xác định', 
						]);
					}
				}else{
					return response()->json(['success'=>false, 
						'message'=>'Tên miền không chính xác', 
					]);
				}
			}else{
				return response()->json(['success'=>false, 
					'message'=>'Không tìm thấy id hosting', 
				]);
			}
		}
	}
	public function hostingDelete()
    {
		if(Auth::check()){
			$idHosting=Input::get('idHosting');
			$hosting=Hosting::where('user_id','=',Auth::user()->id)->where('id','=',$idHosting)->where('type','=','host_cungcap')->first(); 
			if(!empty($hosting->id)){
				$dataHosting=json_decode($hosting->content); 
				$cpanel = new \Gufy\CpanelPhp\Cpanel([
					  'host'        =>  'https://host.cungcap.net:2087', 
					  'username'    =>  'root', 
					  'auth_type'   =>  'hash', 
					  'password'    =>  'NE1FIY5EDECUIC1OZRNI0ZGG6JRP04CG',
				]);
				$accounts = $cpanel->allQuery('removeacct',['user' => $dataHosting->userName]); 
				$hosting->delete(); 
				return response()->json(['success'=>true, 
					'message'=>'Xóa hosting thành công', 
				]);
			}else{
				return response()->json(['success'=>false, 
					'message'=>'Không tìm thấy id hosting', 
				]);
			}
		}
	}
	public function hostingList()
    {
		if(Auth::check()){
			$getHosting=Hosting::where('user_id','=',Auth::user()->id)->paginate(8); 
			$view = array(
				'getHosting'=>$getHosting
			);
			return $this->_theme->scope('hosting.list', $view)->render(); 
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	public function getLoggedInUrl() {
		 $fields = array('user' => 'evnyrkikhosting', 'pass' => 'iN3TG3A2O7eA', 'goto_uri' => '/');
		 $curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($curl, CURLOPT_HEADER,0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_URL, 'https://nethost-2311.inet.vn:2083/');
		curl_setopt($curl, CURLOPT_POSTFIELDS, "user=evnyrkikhosting&pass=iN3TG3A2O7eA");
		$f = curl_exec($curl);
		$h = curl_getinfo($curl);
		curl_close($curl);
		if ($f == true and strpos($h['url'],"cpsess"))
		{
			// Get the cpsess part of the url
			$pattern="/.*?(\/cpsess.*?)\/.*?/is";
			$preg_res=preg_match($pattern,$h['url'],$cpsess);
		}
dd($h); 
		// If we have a session then return it otherwise return empty string
		return (isset($cpsess[1])) ? $cpsess[1] : "";
    }
}
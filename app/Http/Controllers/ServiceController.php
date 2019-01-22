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
use App\Model\Channel; 
use App\Model\Channel_join; 
use App\Model\Domain; 
use App\Model\Domain_join;
use App\Model\Hosting; 
use App\Model\Hosting_join;
use App\Model\Cloud; 
use App\Model\Cloud_join;
use App\Model\Mail_server; 
use App\Model\Mail_server_join; 
use App\Model\Services; 
use App\Model\Services_attribute; 
use Carbon\Carbon;
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
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
class ServiceController extends Controller
{
	protected $_domain; 
	protected $_channel; 
	protected $_channelColor; 
	protected $_theme; 
	protected $_parame; 
	protected $_security; 
	public function __construct(){
		$this->_security=false; 
		$this->_parame=Route::current()->parameters(); 
		$this->_domain=WebService::getDomain($this->_parame['domain']); 
		$this->beforeFilter(function()
		{
			if(empty($this->_domain->domain)){
				return Redirect::to('//'.config('app.url'));
			}elseif($this->_domain->domain_primary!='default'){
				foreach($this->_domain->domainJoinChannel->channel->domainAll as $domain){
					if($domain->domain->domain_primary=='default'){
						return Redirect::to('//'.$domain->domain->domain);
					}
				}
			}else{
				$this->_channel=$this->_domain->domainJoinChannel->channel; 
				$this->_theme=Theme::uses($this->_channel->channelAttributeTheme->theme->temp_location)->layout('default'); 
				//$this->_channel->increment('channel_view',1);
				if(Auth::check()){
					$user=Auth::user(); 
					if($user->hasRole(['admin', 'manage'])){
						$this->_security=true;
					}
					$getRoleChannel=Channel_role::where('parent_id','=',$this->_channel->id)->where('user_id','=',Auth::user()->id)->first(); 
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
				view()->share(
					'channel',array(
						'domain'=>$this->_domain, 
						'info'=>$this->_channel, 
						'security'=>$this->_security, 
						'category'=>$this->_channel->getCategory, 
						'theme'=>$this->_theme, 
						'color'=>(!empty($this->_channel->channelAttributeColor->channel_attribute_value)) ? json_decode($this->_channel->channelAttributeColor->channel_attribute_value) : false
					)
				);
			}
		});
	}
	public function listExpired(){
		$getDomain=Domain::where('date_end','<',Carbon::now()->addMonth(1)->format('Y-m-d H:i:s'))->where('domain_location','=','register')->where('status','=','active')->get(); 
		$getHosting=Hosting::where('date_end','<',Carbon::now()->addMonth(1)->format('Y-m-d H:i:s'))->where('status','!=','delete')->get(); 
		$getCloud=Cloud::where('date_end','<',Carbon::now()->addMonth(1)->format('Y-m-d H:i:s'))->where('status','!=','delete')->get(); 
		$getMailServer=Mail_server::where('date_end','<',Carbon::now()->addMonth(1)->format('Y-m-d H:i:s'))->where('status','!=','delete')->get(); 
		if(count($getDomain)>0){
			foreach($getDomain as $domain){
				$serviceExpired[]=[
					'channel'=>$domain->domainJoinChannel->channel,
					'service_type'=>'domain',
					'service_id'=>$domain->id,
					'service_name'=>$domain->domain, 
					'service_date_begin'=>$domain->date_begin, 
					'service_date_end'=>$domain->date_end, 
					'service_attribute'=>Services_attribute::find($domain->service_attribute_id),
				]; 
			}
		}
		if(count($getHosting)>0){
			foreach($getHosting as $hosting){
				$serviceExpired[]=[
					'channel'=>$hosting->hostingJoinChannel->channel,
					'service_type'=>'hosting',
					'service_id'=>$hosting->id,
					'service_name'=>$hosting->name, 
					'service_date_begin'=>$hosting->date_begin, 
					'service_date_end'=>$hosting->date_end, 
					'service_attribute'=>Services_attribute::find($hosting->service_attribute_id),
				]; 
			}
		}
		if(count($getCloud)>0){
			foreach($getCloud as $cloud){
				$serviceExpired[]=[
					'channel'=>$cloud->cloudJoinChannel->channel,
					'service_type'=>'cloud',
					'service_id'=>$cloud->id,
					'service_name'=>$cloud->name, 
					'service_date_begin'=>$cloud->date_begin, 
					'service_date_end'=>$cloud->date_end, 
					'service_attribute'=>Services_attribute::find($cloud->service_attribute_id),
				];
			}
		}
		if(count($getMailServer)>0){
			foreach($getMailServer as $mail_server){
				$serviceExpired[]=[
					'channel'=>$mail_server->mail_serverJoinChannel->channel,
					'service_type'=>'mail_server',
					'service_id'=>$mail_server->id,
					'service_name'=>$mail_server->name, 
					'service_date_begin'=>$mail_server->date_begin, 
					'service_date_end'=>$mail_server->date_end, 
					'service_attribute'=>Services_attribute::find($mail_server->service_attribute_id),
				]; 
			}
		}
		if(count($serviceExpired)>0){
			foreach ($serviceExpired as $data) {
				$id = $data['channel']->id;
				if (isset($resultList[$id])) {
					$resultList[$id][] = $data;
				}else{
					$resultList[$id] = array($data);
				}
			}
			$totalPrice=0; 
			$totalService=0; 
			foreach($resultList as $key => $listService){
				foreach($listService as $service){
					$totalPrice+=$service['service_attribute']->price_re_order; 
					$totalService++; 
				}
			}
			$return = array(
				'totalPrice'=>$totalPrice, 
				'totalService'=>$totalService, 
				'resultList'=>$resultList
			);
			return $this->_theme->scope('admin.listServiceExpired', $return)->render();
			//echo 'Tổng thanh toán: '.Site::price($totalPrice); 
			//print_r($resultList); 
		}
	}
	public function getAttributeById()
    {
		$serviceId=Input::get('serviceId'); 
		$service=Services::find($serviceId); 
		if(!empty($service->services_name)){
			return response()->json(['success'=>true, 
				'message'=>'Danh sách dịch vụ', 
				'serviceAttributeAll'=>$service->attributeAll
			]);
		}else{
			return response()->json(['success'=>false, 
				'message'=>'Không tìm thấy dịch vụ', 
			]);
		}
	}
}
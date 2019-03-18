<?php 
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Cron_job;
use App\Model\Messages;
use WebService;
use App\User;
use App\Model\History; 
use App\Model\Channel; 
use App\Model\Channel_join; 
use App\Model\Channel_join_region; 
use App\Model\Channel_join_subregion; 
use App\Model\Channel_join_district; 
use App\Model\Channel_join_ward; 
use App\Model\Channel_join_field; 
use App\Model\Channel_attribute; 
use App\Model\Channel_role; 
use App\Model\Posts; 
use App\Model\Domain; 
use App\Model\Domain_join;
use App\Model\Domain_join_channel;
use App\Model\Domain_attribute; 
use App\Model\Domain_data; 
use App\Model\Hosting; 
use App\Model\Hosting_join;
use App\Model\Hosting_url_login;
use App\Model\Cloud; 
use App\Model\Cloud_join;
use App\Model\Mail_server; 
use App\Model\Mail_server_join; 
use App\Model\Services; 
use App\Model\Services_attribute; 
use App\Model\Company; 
use App\Model\Company_join; 
use App\Model\Company_join_channel; 
use App\Model\Company_join_channel_parent; 
use App\Model\Company_join_field; 
use App\Model\Company_join_address; 
use App\Model\Fields;
use App\Model\Address;
use App\Model\Address_join_region;
use App\Model\Address_join_subregion;
use App\Model\Address_join_district;
use App\Model\Address_join_ward;
use App\Model\Regions;
use App\Model\Subregions;
use App\Model\Region_district;
use App\Model\Region_ward;
use App\Model\Comments; 
use App\Model\Comments_join; 
use App\Model\Comments_attribute; 
use App\Model\Process_service; 
use App\Model\Feed; 
use Agent;
use DB;
use DateTime;
use Carbon\Carbon;
use Mail;
use Str; 
use Site;
use Route; 
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use App\Model\SiteLink;
use App\Model\Site_attribute;
use App\Model\Site_url; 
use App\Model\Urlreferer;
use App\Model\Keywords; 
use App\Model\Keywords_attribute; 
use App\Model\News; 
use App\Http\Controllers\DomainController;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client; 
use File;
class CronController extends ConstructController
{
	public $_site=array(); 
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
	public function __construct(){
		parent::__construct(); 
	}
	public function insertDomain(){
        $getDomain=DB::connection('mongodb_old')->collection('note')->where('type','domain')
            ->where('index','<',3)->limit(1000)->get();
        foreach ($getDomain as $domain){

        }
    }
	public function index()
    {
		//return false; 
		set_time_limit(15); 
        if($this->_parame['type']=='send_message'){
			$cron_job=Cron_job::where('type','=','send_message')->first();
			if(!empty($cron_job->id)) {
				$current_date = date('Y-m-d H:i:s');
				$datetime_start = new DateTime($cron_job->date_add_update);
				$datetime_end = new DateTime($current_date);
				$since_start = $datetime_start->diff($datetime_end);
				$minutes=$since_start->i;
				if($minutes>=1){
					$update_scron_job=Cron_job::where('type','=','send_message')->update(array('date_add_update'=>Carbon::now()->format('Y-m-d H:i:s'))); 
					$message=Messages::where('message_send','=','pending')
					->where('message_send_replay','<=',2)
					->limit(2)
					->get(); 
					if(count($message)>0){
						foreach($message as $msgValue){
							$msgValue->increment('message_send_replay',1); 
							if($msgValue->type=='select'){
								//Messages::where('id',$msgValue->id)->increment('message_send_replay', 1);
								$getFromUser=User::find($msgValue->from); 
								$getChannel=Channel::find($msgValue->to); 
								if(!empty($getChannel->joinEmail[0]->email->email_address) && !empty($getFromUser->email)){
									$data=[
										'msgValue'=>$msgValue, 
										'user'=>$getFromUser, 
										'channel'=>$getChannel, 
										'message_title'=>$msgValue->message_title, 
										'message_body'=>$msgValue->message_body
									]; 
									Mail::send('emails.messageSelectToChannel', $data, function ($msg) use ($data) {
										$msg->from(config('app.app_email'),$data['user']->name);
										$msg->replyTo($data['user']->joinEmail[0]->email->email_address, $data['user']->name); 
										$msg->to($data['channel']->joinEmail[0]->email->email_address, $data['channel']->channel_name)
											//->cc(config('app.app_email'))
											->subject($data['message_title']);
									});
									if(count(Mail::failures())>0){
									   Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'replay')); 
									}else{
										Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'delivery'));  
										echo '<p>Đã gửi tin nhắn đến '.$getChannel->joinEmail[0]->email->email_address.'</p>';
									}
								}else{
									Messages::where('id',$msgValue->id)
									->update(array('message_send'=>'error')); 
								}
							}elseif($msgValue->type=='buyNow'){
								//Messages::where('id',$msgValue->id)->increment('message_send_replay', 1);
								$getChannel=Channel::find($msgValue->to); 
								if(!empty($getChannel->joinEmail[0]->email->email_address)){
									$messageArray=json_decode($msgValue->message_body); 
									if(!empty($messageArray->userEmail) && !empty($messageArray->userName)){
										$userEmail=$messageArray->userEmail; 
										$userName=$messageArray->userName;
									}else{
										$userEmail=$getChannel->joinEmail[0]->email->email_address; 
										$userName=$getChannel->channel_name;
									}
									$data=[
										'msgValue'=>$msgValue, 
										'channel'=>$getChannel, 
										'message_title'=>$msgValue->message_title, 
										'message_body'=>$msgValue->message_body, 
										'userEmail'=>$userEmail, 
										'userName'=>$userName
									]; 
									Mail::send('emails.buyNow', $data, function ($msg) use ($data) {
										$msg->from(config('app.app_email'),$data['channel']->channel_name);
										$msg->replyTo($data['userEmail'], $data['userName']); 
										$msg->to($data['channel']->joinEmail[0]->email->email_address, $data['channel']->channel_name)
											->cc(config('app.mail_admin'))
											->subject($data['message_title']);
									});
									if(count(Mail::failures())>0){
									   Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'replay')); 
									}else{
										Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'delivery')); 
										echo '<p>Đã gửi tin nhắn đến '.$getChannel->joinEmail[0]->email->email_address.'</p>';
									}
								}else{
									Messages::where('id',$msgValue->id)
									->update(array('message_send'=>'error'));
								}
								
							}elseif($msgValue->type=='userRegister'){
								//Messages::where('id',$msgValue->id)->increment('message_send_replay', 1);
								$getFromUser=User::find($msgValue->to); 
								$getChannel=Channel::find($msgValue->from); 
								if(!empty($getChannel->joinEmail[0]->email->email_address) && !empty($getFromUser->email)){
									$data=[
										'msgValue'=>$msgValue, 
										'user'=>$getFromUser, 
										'channel'=>$getChannel, 
										'message_title'=>$msgValue->message_title, 
										'message_body'=>$msgValue->message_body
									]; 
									Mail::send('emails.userRegister', $data, function ($msg) use ($data) {
										$msg->from(config('app.app_email'),$data['channel']->channel_name);
										$msg->to($data['user']->joinEmail[0]->email->email_address, $data['user']->name)
											->cc(config('app.mail_admin'))
											->subject($data['message_title']);
									});
									if(count(Mail::failures())>0){
									   Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'replay')); 
									}else{
										Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'delivery')); 
										echo '<p>Đã gửi tin nhắn đến '.$getFromUser->joinEmail[0]->email->email_address.'</p>';
									}
								}else{
									Messages::where('id',$msgValue->id)
									->update(array('message_send'=>'error'));
								}
							}elseif($msgValue->type=='channelAdd'){
								//Messages::where('id',$msgValue->id)->increment('message_send_replay', 1);
								$getFromUser=User::find($msgValue->to); 
								$getChannel=Channel::find($msgValue->from); 
								if(!empty($getChannel->joinEmail[0]->email->email_address) && !empty($getFromUser->joinEmail[0]->email->email_address)){
									$data=[
										'msgValue'=>$msgValue, 
										'user'=>$getFromUser, 
										'channel'=>$getChannel, 
										'message_title'=>$msgValue->message_title, 
										'message_body'=>$msgValue->message_body
									]; 
									Mail::send('emails.messageChannelAddToUser', $data, function ($msg) use ($data) {
										$msg->from(config('app.app_email'),$data['channel']->channel_name);
										$msg->to($data['user']->joinEmail[0]->email->email_address, $data['user']->name)
											->cc(config('app.mail_admin'))
											->subject($data['message_title']);
									});
									if(count(Mail::failures())>0){
									   Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'replay')); 
									}else{
										Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'delivery')); 
										echo '<p>Đã gửi tin nhắn đến '.$getFromUser->joinEmail[0]->email->email_address.'</p>';
									}
								}else{
									Messages::where('id',$msgValue->id)
									->update(array('message_send'=>'error')); 
								}
							}elseif($msgValue->type=='domainAdd'){
								//Messages::where('id',$msgValue->id)->increment('message_send_replay', 1);
								$getUser=User::find($msgValue->to); 
								$getChannel=Channel::find($msgValue->from); 
								if(!empty($getChannel->joinEmail[0]->email->email_address) && !empty($getUser->joinEmail[0]->email->email_address)){
									$data=[
										'msgValue'=>$msgValue, 
										'user'=>$getUser, 
										'channel'=>$getChannel, 
										'message_title'=>$msgValue->message_title, 
										'message_body'=>$msgValue->message_body
									]; 
									Mail::send('emails.domainAdd', $data, function ($msg) use ($data) {
										$msg->from(config('app.app_email'),$data['channel']->channel_name);
										$msg->to($data['user']->joinEmail[0]->email->email_address, $data['user']->name)
											->cc(config('app.mail_admin'))
											->subject($data['message_title']);
									});
									if(count(Mail::failures())>0){
									   Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'replay')); 
									}else{
										Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'delivery')); 
										echo '<p>Đã gửi tin nhắn đến '.$getUser->joinEmail[0]->email->email_address.'</p>';
									}
								}else{
									Messages::where('id',$msgValue->id)
									->update(array('message_send'=>'error')); 
								}
							}elseif($msgValue->type=='contact'){
								//Messages::where('id',$msgValue->id)->increment('message_send_replay', 1);
								$arrayMsg=json_decode($msgValue->message_body); 
								$getChannel=Channel::find($msgValue->to); 
								if($arrayMsg->requestFrom=='ip'){
									$user_name=$arrayMsg->name; 
									$user_email=$arrayMsg->email; 
								}elseif($arrayMsg->requestFrom=='user'){
									$getFromUser=User::find($msgValue->from); 
									$user_name=$getFromUser->name; 
									$user_email=$getFromUser->joinEmail[0]->email->email_address; 
								}
								if(!empty($getChannel->joinEmail[0]->email->email_address)){
									$data=[
										'msgValue'=>$msgValue, 
										'user_name'=>$user_name, 
										'user_email'=>$user_email, 
										'channel'=>$getChannel, 
										'message_title'=>$msgValue->message_title, 
										'message_body'=>$msgValue->message_body
									]; 
									Mail::send('emails.messageContact', $data, function ($msg) use ($data) {
										$msg->from(config('app.app_email'),$data['user_name']);
										$msg->replyTo($data['user_email'], $data['user_name']); 
										$msg->to($data['channel']->joinEmail[0]->email->email_address, $data['channel']->channel_name)
											//->cc(config('app.app_email'))
											->subject($data['message_title']);
									});
									if(count(Mail::failures())>0){
									   Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'replay')); 
									}else{
										Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'delivery')); 
										echo '<p>Đã gửi tin nhắn đến '.$getChannel->joinEmail[0]->email->email_address.'</p>';
									}
								}else{
									Messages::where('id',$msgValue->id)
									->update(array('message_send'=>'error')); 
								}
							}elseif($msgValue->type=='domainAddMsgToReseller'){
								//Messages::where('id',$msgValue->id)->increment('message_send_replay', 1);
								$arrayMsg=json_decode($msgValue->message_body); 
								$getChannel=Channel::find($msgValue->to); 
								$getFromUser=User::find($msgValue->from); 
								if(!empty($getChannel->joinEmail[0]->email->email_address)){
									$data=[
										'msgValue'=>$msgValue, 
										'user'=>$getFromUser, 
										'channel'=>$getChannel, 
										'message_title'=>$msgValue->message_title, 
										'message_body'=>$msgValue->message_body
									]; 
									Mail::send('emails.messageDomainAddToReseller', $data, function ($msg) use ($data) {
										$msg->from(config('app.app_email'),$data['channel']->channel_name);
										$msg->replyTo($data['user']->joinEmail[0]->email->email_address, $data['user']->name); 
										$msg->to($data['channel']->joinEmail[0]->email->email_address, $data['channel']->channel_name)
											//->cc(config('app.app_email'))
											->subject($data['message_title']);
									});
									if(count(Mail::failures())>0){
									   Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'replay')); 
									}else{
										Messages::where('id',$msgValue->id)
										->update(array('message_send'=>'delivery')); 
										echo '<p>Đã gửi tin nhắn đến '.$getChannel->joinEmail[0]->email->email_address.'</p>';
									}
								}else{
									Messages::where('id',$msgValue->id)
									->update(array('message_send'=>'error')); 
								}
							}
						}
					}else{
						echo 'Không có tin nhắn nào';
					}
				}
				else
				{
					echo "Thời gian Cron chưa hoạt động! ";
				}
			}else{
				echo 'Khong tim thay cron';
			}
		}
		if($this->_parame['type']=='send_service_expired'){
			//return false; 
			$getChannelPri=Channel::where('channel_date_end','<',Carbon::now()->addMonth(1)->format('Y-m-d H:i:s'))->where('service_attribute_id','!=',1)->where('channel_status','=','active')->where('notify','<',6)->get();
			//dd($getChannelPri); 
			$getDomain=Domain::where('date_end','<',Carbon::now()->addMonth(1)->format('Y-m-d H:i:s'))->where('domain_location','=','register')->where('status','=','active')->where('notify','<',6)->get(); 
			
			$getHosting=Hosting::where('date_end','<',Carbon::now()->addMonth(1)->format('Y-m-d H:i:s'))->where('status','!=','delete')->get(); 
			
			$getCloud=Cloud::where('date_end','<',Carbon::now()->addMonth(1)->format('Y-m-d H:i:s'))->where('status','!=','delete')->get(); 
			 
			$getMailServer=Mail_server::where('date_end','<',Carbon::now()->addMonth(1)->format('Y-m-d H:i:s'))->where('status','!=','delete')->get(); 
			if(count($getChannelPri)>0){
				foreach($getChannelPri as $channel){
					$serviceExpired[]=[
						'channel'=>$channel,
						'service_type'=>'channel',
						'service_id'=>$channel->id,
						'service_name'=>$channel->channel_name, 
						'service_date_begin'=>$channel->channel_date_begin, 
						'service_date_end'=>$channel->channel_date_end, 
						'service_attribute'=>Services_attribute::find($channel->service_attribute_id), 
						'notify'=>$channel->notify, 
						'time_notify'=>$channel->time_send_notify, 
					]; 
				}
			}
			
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
						'notify'=>$domain->notify, 
						'time_notify'=>$domain->time_notify, 
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
						'notify'=>$hosting->notify, 
						'time_notify'=>$hosting->time_notify, 
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
						'notify'=>$cloud->notify, 
						'time_notify'=>$cloud->time_notify, 
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
						'notify'=>$mail_server->notify, 
						'time_notify'=>$mail_server->time_notify, 
					]; 
				}
			}
			if(count($serviceExpired)>0){
				foreach ($serviceExpired as $data) {
					
					$id = $data['channel']->id;
					if (isset($result[$id])) {
						$result[$id][] = $data;
					}else{
						$result[$id] = array($data);
					}
				}
				$totalPrice=0; 
				foreach($result as $key => $listService){
					$getChannel=Channel::find($key); 
					if(Carbon::parse($getChannel->time_send_notify)->addDays(5)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s') && $getChannel->notify<=5){
						if(!empty($getChannel->joinEmail[0]->email->email_address)){
							$data=[
								'channel'=>$getChannel, 
								'listService'=>$listService, 
								'message_title'=>'Thông báo gia hạn '.$listService[0]['service_name']
							]; 
							Mail::send('emails.notifyServiceExpired', $data, function ($msg) use ($data) {
								$msg->from(config('app.app_email'),$data['channel']->channel_name);
								//$msg->replyTo($data['user']->emailJoin->email->email_address, $data['user']->name); 
								$msg->to($data['channel']->joinEmail[0]->email->email_address, $data['channel']->channel_name)
									->cc(config('app.mail_admin'))
									->subject($data['message_title']);
							});
							if(count(Mail::failures())>0){
								
							}else{
								/*
								foreach($listService as $service){
									if($service['service_type']=='channel'){
										$channel=Channel::find($service['service_id']); 
										$channel->increment('notify', 1); 
									}
									if($service['service_type']=='domain'){
										$domain=Domain::find($service['service_id']); 
										$domain->increment('notify', 1); 
									}
									if($service['service_type']=='hosting'){
										$hosting=Hosting::find($service['service_id']); 
										$hosting->increment('notify', 1); 
									}
									if($service['service_type']=='cloud'){
										$cloud=Cloud::find($service['service_id']); 
										$cloud->increment('notify', 1); 
									}
									if($service['service_type']=='mail_server'){
										$mail_server=Mail_server::find($service['service_id']); 
										$mail_server->increment('notify', 1); 
									}
								}
								*/
								$getChannel->increment('notify', 1); 
								$getChannel->time_send_notify=Carbon::now()->format('Y-m-d H:i:s'); 
								$getChannel->save(); 
							}
						}else{
							echo 'Không tìm thấy Email của kênh để gửi thông báo! ';
						}
						foreach($listService as $service){
							$totalPrice+=$service['service_attribute']->price_re_order;
						}
						echo 'Tổng thanh toán: '.Site::price($totalPrice); 
						print_r($result); 
					}else{
						echo '<p>'.$getChannel->channel_name.' chưa tới thời gian thông báo</p>'; 
					}
				}
			}
		}
		if($this->_parame['type']=='import_company'){
			return false; 
			$startCron=Cron_job::where('type','=','import_company')->first();
			if(Carbon::parse($startCron->date_add_update)->addMinute()->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
				$dataContent=file_get_contents('https://thongtindoanhnghiep.co/api/company?r=10&p='.$startCron->page_begin); 
				$dataJson=json_decode($dataContent); 
				$count=0; 
				foreach($dataJson->LtsItems as $value){
					$dataCompany=file_get_contents('https://thongtindoanhnghiep.co/api/company/'.$value->MaSoThue); 
					$dataCompanyJson=json_decode($dataCompany); 
					$regionWard=Region_ward::where('SolrID','=','/VN'.$dataCompanyJson->PhuongXaTitleAscii)->first();
					$regionDistrict=Region_district::where('SolrID','=','/VN'.$dataCompanyJson->QuanHuyenTitleAscii)->first();  
					$regionCity=Subregions::where('SolrID','=','/VN'.$dataCompanyJson->TinhThanhTitleAscii)->first(); 
					$array_customers = array(
						'company_name' => (!empty($dataCompanyJson->Title)) ? $dataCompanyJson->Title : $dataCompanyJson->TitleEn, 
						'company_name_en' => (!empty($dataCompanyJson->TitleEn)) ? $dataCompanyJson->TitleEn : $dataCompanyJson->Title, 
						'company_address' => $dataCompanyJson->DiaChiCongTy, 
						'nganh_chinh'=>(!empty(Fields::where('NganhNgheID','=',$dataCompanyJson->NganhNgheID)->first()->id)) ? Fields::where('NganhNgheID','=',$dataCompanyJson->NganhNgheID)->first()->id : '', 
						'company_ward'=>(!empty($regionWard->id)) ? $regionWard->id : '', 
						'company_district' => (!empty($regionDistrict->id)) ? $regionDistrict->id : '',
						'company_subregion' => (!empty($regionCity->id)) ? $regionCity->id : '',
						'company_region' => 704,
						'company_tax_code' => $dataCompanyJson->MaSoThue, 
						'noi_dang_ky_quan_ly'=>$dataCompanyJson->NoiDangKyQuanLy_CoQuanTitle, 
						'ngay_cap' => $dataCompanyJson->NgayCap,
						'admin_name' => $dataCompanyJson->ChuSoHuu,
						'admin_phone' => isset($dataCompanyJson->NoiDangKyQuanLy_DienThoai) ? $dataCompanyJson->NoiNopThue_DienThoai : $dataCompanyJson->NoiDangKyQuanLy_DienThoai,
						'SolrID' => Str::slug($dataCompanyJson->SolrID),
						'company_created_at' => Carbon::now()->format('Y-m-d H:i:s'), 
						'company_updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'company_status' =>'active',
						'company_type' => 'api'
					);
					//add table category
					if(!empty($dataCompanyJson->MaSoThue)){
						$checkCustomer=Company::where('company_tax_code','=',$dataCompanyJson->MaSoThue)->get()->count();
						if($checkCustomer>0){
							echo 'Doanh nghiệp đã tồn tại'; 
						}else{
							$idCompany = Company::insertGetId($array_customers);
							//$id_insert = $respons->id;
							if ($idCompany > 0) {
								foreach($dataCompanyJson->DSNganhNgheKinhDoanhID as $idNganhnghe){
									$idCategories=Fields::where('NganhNgheID','=',$idNganhnghe)->first()->id; 
									if(!empty($idCategories)){
										$category_insert = array(
											'field_id'=>$idCategories, 
											'company_id'=>$idCompany, 
										);
										Company_join_field::insertGetId($category_insert); 
									}
								}
								$address=explode(',',$dataCompanyJson->DiaChiCongTy); 
								$idAddress=Address::insertGetId(array(
									'address'=>$address[0], 
									'address_full'=>$dataCompanyJson->DiaChiCongTy, 
									'status'=>'pending', 
									'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
									'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
								)); 
								if($idAddress){
									Company_join_address::insertGetId(array(
										'company_id'=>$idCompany, 
										'address_id'=>$idAddress
									)); 
									Address_join_region::insertGetId(array(
										'region_id'=>704, 
										'address_id'=>$idAddress
									)); 
									if(!empty(Subregions::find($regionCity->id)->id)){
										Address_join_subregion::insertGetId(array(
											'subregion_id'=>$regionCity->id, 
											'address_id'=>$idAddress, 
										));
									}
									if(!empty(Region_district::find($regionDistrict->id)->id)){
										Address_join_district::insertGetId(array(
											'district_id'=>$regionDistrict->id, 
											'address_id'=>$idAddress, 
										));
									}
									if(!empty(Region_ward::find($regionWard->id)->id)){
										Address_join_ward::insertGetId(array(
											'ward_id'=>$regionWard->id, 
											'address_id'=>$idAddress, 
										));
									}
								}
							}
							echo 'Đã import <b>'.$dataCompanyJson->Title.'</b> - Id: '.$idCompany.' - Mst: '.$dataCompanyJson->MaSoThue.'<br>'; 
						}
					}
				}
				$startCron->increment('page_begin', 1); 
				$startCron->date_add_update=Carbon::now()->format('Y-m-d H:i:s'); 
				$startCron->save(); 
			}else{
				echo 'Chưa tới thời gian hoạt động Cron! '; 
			}
		}
		if($this->_parame['type']=='payment'){
			return 'false';
			$scron_job_exit=Cron_job::where('type','=','payment')->get()->count();
			if($scron_job_exit>0) {
				$start_date=Cron_job::where('type','=','payment')->first()->date_add_update;
				$current_date = date('Y-m-d H:i:s');
				$datetime_start = new DateTime($start_date);
				$datetime_end = new DateTime($current_date);
				$since_start = $datetime_start->diff($datetime_end);
				$minutes=$since_start->i;
				if($minutes>=2){
					$update_scron_job=Cron_job::where('type','=','payment')->update(array('date_add_update'=>Carbon::now()->format('Y-m-d H:i:s'))); 
					$getPayment=History_pay_order::where('status','=','sending')->get(); 
					if(count($getPayment)>0){
						foreach($getPayment as $payment){
							if($payment->service=='channel'){
								if($payment->amount > 0){
									$percentReseller=25/100; 
									$percentAdmin=75/100; 
									$totalPriceToReseller=$payment->amount*$percentReseller; 
									$totalPriceToAdmin=$payment->amount*$percentAdmin; 
									
									$sendPaymentToReseller =array(
										'money'=> $totalPriceToReseller, 
										'currencyCode'=>$payment->currencyCode, 
										'pay_type'=>'add', 
										'user_id'=> $payment->sender_to, 
										'pay_from'=>$payment->id, 
										'created_at'=> Carbon::now()->format('Y-m-d H:i:s'), 
										'update_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
									);
									$responsSendToReseller = Finance::create($sendPaymentToReseller); 
									if($responsSendToReseller->id > 0){
										$infoUserFrom=User::where('id','=',$payment->sender_from)->first(); 
										$infoTemplateSetting=Template_setting::where('id','=',$payment->template_setting_id)->first(); 
										$financeReseller=Finance::where('user_id','=',$payment->sender_to)->sum('money'); 
										$data_message_to_reseller = array(
											'message_group' => date_timestamp_get(date_create())+$payment->sender_to,
											'from_member' => 1,
											'to_member' => $payment->sender_to,
											'message_title' => 'Tài khoản được +'.Site::formatMoney($totalPriceToReseller,true).config('app.vpc_Currency').' từ '.$infoUserFrom->name,
											'message_body' => 'Bạn được cộng +'.Site::formatMoney($totalPriceToReseller,true).config('app.vpc_Currency').' vào tài khoản từ thành viên '.$infoUserFrom->name. ' thông qua việc tạo kênh '.$infoTemplateSetting->domain.'. Số dư tài khoản '.Site::formatMoney($financeReseller,true).config('app.vpc_Currency')

										);
										Messages::create($data_message_to_reseller); 
										
										$sendPaymentToAdmin =array(
											'money'=> $totalPriceToAdmin, 
											'currencyCode'=>$payment->currencyCode, 
											'pay_type'=>'add', 
											'user_id'=> 1, 
											'pay_from'=>$payment->id, 
											'created_at'=> Carbon::now()->format('Y-m-d H:i:s'), 
											'update_at'=>Carbon::now()->format('Y-m-d H:i:s'),  
										);
										$responsSendToAdmin = Finance::create($sendPaymentToAdmin); 
										if($responsSendToAdmin->id > 0){
											$infoUserFromReseller=User::where('id','=',$payment->sender_to)->first(); 
											$financeAdmin=Finance::where('user_id','=',1)->sum('money'); 
											$data_message_to_admin = array(
												'message_group' => date_timestamp_get(date_create())+1,
												'from_member' => $payment->sender_to,
												'to_member' => 1,
												'message_title' => 'Tài khoản được +'.Site::formatMoney($totalPriceToAdmin,true).config('app.vpc_Currency').' từ '.$infoUserFromReseller->name,
												'message_body' => 'Bạn được cộng +'.Site::formatMoney($totalPriceToAdmin,true).config('app.vpc_Currency').' vào tài khoản từ đại lý '.$infoUserFromReseller->name. ' thông qua việc tạo kênh '.$infoTemplateSetting->domain.'. Số dư tài khoản '.Site::formatMoney($financeAdmin,true).config('app.vpc_Currency')

											);
											Messages::create($data_message_to_admin); 
											DB::table('user_voucher')
											->where('string_voucher','=',$payment->sender_code)
											->increment('number_use',1);
										}
										/*
										$phoneNumber=WebService::formatPhoneNumber('903706288','+84'); 
										$smsMessage = urlencode('Tai khoan +'.Site::formatMoney($totalPriceToAdmin,true).config('app.vpc_Currency').' tu '.WebService::vn_str_filter($infoUserFromReseller->name).' So du tai khoan '.Site::formatMoney($financeAdmin,true).config('app.vpc_Currency'));
										$url='http://center.fibosms.com/Service.asmx/SendMaskedSMS?clientNo=CL8390&clientPass=n582mDAT8&senderName=Europe%20Sky&phoneNumber='.$phoneNumber.'&smsMessage='.$smsMessage.'&smsGUID=123456&serviceType=4929';
										$getSendSMS=file_get_contents($url); 
										echo $getSendSMS;  
										*/
									}
									$updateHistoryPayment=History_pay_order::where('id','=',$payment->id)->update(array('status'=>'received', 'update_at'=>Carbon::now()->format('Y-m-d H:i:s'))); 
									echo '<p>Đã chuyển cho Reseller số tiền '.$totalPriceToReseller.'</p>'; 
									echo '<p>Đã chuyển cho Admin số tiền '.$totalPriceToAdmin.'</p>';
								}
							}
						}
					}else{
						echo 'Không có thông tin thanh toán nào'; 
					}
				}else{
					echo 'Chưa tới thời gian hoạt động Cron! '; 
				}
			}else{
				echo 'Cron không hoạt động! '; 
			}
		}
		if($this->_parame['type']=='pay_reseller'){
			return 'false';
			$cron_job=Cron_job::where('type','=','pay_reseller')->first();
			if(count($cron_job)>0) {
				$current_date = date('Y-m-d H:i:s');
				$datetime_start = new DateTime($cron_job->date_add_update);
				$datetime_end = new DateTime($current_date);
				$since_start = $datetime_start->diff($datetime_end);
				$minutes=$since_start->i;
				if($minutes>=2){
					$update_scron_job=Cron_job::where('type','=','pay_reseller')->update(array('date_add_update'=>Carbon::now()->format('Y-m-d H:i:s'))); 
					$getPayment=History_pay_order::where('service','=','channel')
					->where('sender_to','!=',1)
					->where('sender_code','!=',"")
					->where('status','=',"received")
					->where('pay_reseller','=',"unpaid")
					->limit(1)
					->get(); 
					foreach($getPayment as $payment){
						$voucher=User_voucher::where('string_voucher','=',$payment->sender_code)->first(); 
						if(isset($voucher)){
							$percentReseller=25/100; 
							$percentAddMember=$voucher->discount/100; 
							$totalPriceToReseller=$payment->amount*$percentReseller; 
							$priceToMember=$totalPriceToReseller*$percentAddMember; 
							$infoTemplateSetting=Template_setting::where('id','=',$payment->template_setting_id)->first(); 
							$member=Member_join::where('table_join','=','user_voucher')->where('table_id','=',$voucher->id)->first(); 
							if(isset($member)){
								$infoUserReseller=User::where('id','=',$payment->sender_to)->first(); 
								$removePaymentReseller =array(
									'money'=> '-'.$priceToMember, 
									'currencyCode'=>$payment->currencyCode, 
									'pay_type'=>'remove', 
									'user_id'=> $payment->sender_to, 
									'pay_from'=>$payment->id, 
									'created_at'=> Carbon::now()->format('Y-m-d H:i:s'), 
									'update_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								);
								$responsRemovePaymentReseller = Finance::create($removePaymentReseller); 
								if($responsRemovePaymentReseller->id > 0){
									$infoUserMember=User::where('id','=',$member->user_id)->first(); 
									$financeReseller=Finance::where('user_id','=',$payment->sender_to)->sum('money'); 
									$data_message_to_reseller = array(
										'message_group' => date_timestamp_get(date_create())+$payment->sender_to,
										'from_member' => 1,
										'to_member' => $member->user_id,
										'message_title' => 'Tài khoản của bạn -'.Site::formatMoney($priceToMember,true).config('app.vpc_Currency'),
										'message_body' => 'Bạn được -'.Site::formatMoney($priceToMember,true).config('app.vpc_Currency').' chuyển khoản tới '.$infoUserMember->name. ' thông qua việc tạo kênh '.$infoTemplateSetting->domain.'. Số dư tài khoản '.Site::formatMoney($financeReseller,true).config('app.vpc_Currency')

									);
									Messages::create($data_message_to_reseller); 
									$sendPaymentToMember =array(
										'money'=> $priceToMember, 
										'currencyCode'=>$payment->currencyCode, 
										'pay_type'=>'add', 
										'user_id'=> $member->user_id, 
										'pay_from'=>$payment->id, 
										'created_at'=> Carbon::now()->format('Y-m-d H:i:s'), 
										'update_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
									);
									$responsSendToMember = Finance::create($sendPaymentToMember); 
									if($responsSendToMember->id > 0){
										$financeMember=Finance::where('user_id','=',$member->user_id)->sum('money'); 
										$data_message_to_member = array(
											'message_group' => date_timestamp_get(date_create())+$member->user_id,
											'from_member' => $payment->sender_to,
											'to_member' => $member->user_id,
											'message_title' => 'Tài khoản được +'.Site::formatMoney($priceToMember,true).config('app.vpc_Currency').' từ '.$infoUserReseller->name,
											'message_body' => 'Bạn được cộng +'.Site::formatMoney($priceToMember,true).config('app.vpc_Currency').' vào tài khoản từ '.$infoUserReseller->name. ' thông qua việc tạo kênh '.$infoTemplateSetting->domain.'. Số dư tài khoản '.Site::formatMoney($financeMember,true).config('app.vpc_Currency')

										);
										Messages::create($data_message_to_member); 
										History_pay_order::where('id','=',$payment->id)->update(array('pay_reseller'=>'paid', 'update_at'=>Carbon::now()->format('Y-m-d H:i:s'))); 
										echo '<p>Đã chuyển cho Member số tiền '.$priceToMember.'</p>'; 
										echo '<p>Đã trừ đại lý số tiền '.$priceToMember.'</p>';
									}
								}
							}else{
								echo 'Không tìm thấy thành viên nào! '; 
							}
							
						}else{
							echo 'Không tìm thấy mã giảm giá! '; 
						}
					}
				}else{
					echo 'Chưa tới thời gian hoạt động Cron! '; 
				}
			}
		}
		if($this->_parame['type']=='craw_news'){
			return 'false'; 
			$cron_job=Cron_job::where('type','=','craw_news')->first();
			if(count($cron_job)>0) {
				$current_date = date('Y-m-d H:i:s');
				$datetime_start = new DateTime($cron_job->date_add_update);
				$datetime_end = new DateTime($current_date);
				$since_start = $datetime_start->diff($datetime_end);
				$minutes=$since_start->i;
				if($minutes>=1){
					$update_scron_job=Cron_job::where('type','=','craw_news')->update(array('date_add_update'=>Carbon::now()->format('Y-m-d H:i:s'))); 
					$getFeed=Feed::where('updated_at','<',Carbon::now()->format('Y-m-d H:i:s'))->get();
					foreach($getFeed as $dataUrl){
						$dataUrl->updated_at=Carbon::now()->addMinute(5)->format('Y-m-d H:i:s'); 
						$dataUrl->save(); 
						$rss = new \DOMDocument();
						$rss->load($dataUrl->url);
						$feed = array(); 
						foreach ($rss->getElementsByTagName('item') as $node) {
							$source_url = '/<div id="source_url">[^>]*<\/div>/'; 
							$content=preg_replace($source_url,'',$node->getElementsByTagName('encoded')->item(0)->nodeValue).'<p>';
							preg_match('/<div id="source_url">([^>]*?)<\/div>/', $node->getElementsByTagName('encoded')->item(0)->nodeValue, $match); 
							$source=str_replace(' ', '',preg_replace('/^[ \t]*[\r\n]+/m', '', $match[1])); 
							$title=$node->getElementsByTagName('title')->item(0)->nodeValue; 
							$image=$node->getElementsByTagName('image')->item(0)->nodeValue; 
							$description=$node->getElementsByTagName('description')->item(0)->nodeValue; 
							$checkExit=News::where('title_encode','=',base64_encode($title))->first(); 
							if(empty($checkExit->title) && !empty($title) && strlen(strip_tags($content,""))>250){
								News::insertGetId(array(
									'title'=>$title, 
									'title_convert'=>WebService::vn_str_filter(htmlspecialchars(strip_tags($title, ''))), 
									'title_encode'=>base64_encode($title), 
									'image'=>$image, 
									'description'=>$description, 
									'content'=>$content, 
									'source_url'=>$source, 
									'field'=>$dataUrl->field, 
									'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
									'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
									'status'=>'active', 
								)); 
								echo $title.'<p>';
							}
							/*$item = array (
									'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
									'link' => $node->getElementsByTagName('link')->item(0)->nodeValue, 
									'image' => $node->getElementsByTagName('image')->item(0)->nodeValue,
									'pubDate' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
									'description' => $node->getElementsByTagName('description')->item(0)->nodeValue,
									'content' => $node->getElementsByTagName('encoded')->item(0)->nodeValue

									);
							array_push($feed, $item); 
							*/
						} 
					}
				}else{
					echo 'Chua toi thoi gian'; 
				}
			}
		}
		if($this->_parame['type']=='get_url'){
			return 'false'; 
			$cron_job=Cron_job::where('type','=','get_url')->first();
			if(count($cron_job)>0) {
				$current_date = date('Y-m-d H:i:s');
				$datetime_start = new DateTime($cron_job->date_add_update);
				$datetime_end = new DateTime($current_date);
				$since_start = $datetime_start->diff($datetime_end);
				$minutes=$since_start->i;
				if($minutes>=1){
					$update_scron_job=Cron_job::where('type','=','get_url')->update(array('date_add_update'=>Carbon::now()->format('Y-m-d H:i:s'))); 
					$getSiteUrl=Site_url::where('status','pending')->limit(1)->get(); 
					foreach($getSiteUrl as $siteUrl){
						try {
							$client = new Client([
								'headers' => [ 
									'Content-Type' => 'text/html'
								], 
								'connect_timeout' => '2',
								'timeout' => '2',
								'allow_redirects' => [
									'max'             => 5,        // allow at most 10 redirects.
									'strict'          => true,      // use "strict" RFC compliant redirects.
									'referer'         => true,      // add a Referer header
									'protocols'       => ['https'], // only allow https URLs
									'track_redirects' => true
								], 
								'http_errors' => false
							]); 
							//dd($siteUrl->url); 
							$response = $client->request('GET', $siteUrl->url); 
							//dd($response); 
							$getResponse=$response->getBody()->getContents(); 
							$data = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse; 
							//dd($data); 
							//$data = file_get_contents($siteUrl->url, false, $context); 
							if ($data === false) {
								$siteUrl->status='faild'; 
								$siteUrl->save(); 
							}else{
								$regex = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
								preg_match_all($regex, $data, $matches); 
								$pslManager = new PublicSuffixListManager(); 
								$parser = new Parser($pslManager->getList()); 
								$n=0; 
								foreach ($matches[0] as $link) { 
									$domainName = $parser->parseUrl($link);
									$checkExitUrl=Site_url::where('url_encode','=',base64_encode($link))->first(); 
									if(empty($checkExitUrl->url)){ 
										echo 'Link: '.$link.'<p>';
										Site_url::insertGetId(array(
											'url'=>$link, 
											'url_encode'=>base64_encode($link), 
											'parent_id'=>0, 
											'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
											'status'=>'pending'
										)); 
									}
									$checkSiteExit=Domain::where('domain_encode','=',base64_encode($domainName->host->registerableDomain))->first(); 
									if(empty($checkSiteExit->domain) && WebService::is_valid_url($domainName->host->registerableDomain)==true){
										echo 'Domain '.$domainName->host->registerableDomain.'<p>';
										Domain::insertGetId(array(
											'domain'=>$domainName->host->registerableDomain, 
											'domain_encode'=>base64_encode($domainName->host->registerableDomain), 
											'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
											'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
											'status'=>'pending', 
											'insert_by'=>'crawler'
										)); 
									}
									$n++; 
								} 
								$siteUrl->status='active'; 
								$siteUrl->save(); 
							}
						}catch (\GuzzleHttp\Exception\ServerException $e){
							$siteUrl->status='faild'; 
							$siteUrl->save(); 
							echo 'error 1 <p>';
						}catch (\GuzzleHttp\Exception\BadResponseException $e){
							$siteUrl->status='faild'; 
							$siteUrl->save(); 
							echo 'error 2 <p>';
						}catch (\GuzzleHttp\Exception\ClientException $e){
							$siteUrl->status='faild'; 
							$siteUrl->save(); 
							echo 'error 3 <p>';
						}catch (\GuzzleHttp\Exception\ConnectException $e){
							$siteUrl->status='faild'; 
							$siteUrl->save(); 
							echo 'error 4 <p>';
						}catch (\GuzzleHttp\Exception\RequestException $e){
							$siteUrl->status='faild'; 
							$siteUrl->save(); 
							echo 'error 5 <p>';
						}
					}
				}else{
					echo 'chưa hoạt động cron'; 
				}
			}
		}
		if($this->_parame['type']=='get_info_url'){
			return 'false'; 
			$cron_job=Cron_job::where('type','=','get_info_url')->first();
			if(count($cron_job)>0) {
				$current_date = date('Y-m-d H:i:s');
				$datetime_start = new DateTime($cron_job->date_add_update);
				$datetime_end = new DateTime($current_date);
				$since_start = $datetime_start->diff($datetime_end);
				$minutes=$since_start->i;
				if($minutes>=1){
					$update_scron_job=Cron_job::where('type','=','get_info_url')->update(array('date_add_update'=>Carbon::now()->format('Y-m-d H:i:s'))); 
					$getSite=Domain::where('status','pending')->where('insert_by','crawler')->where('craw_replay','<=',2)->limit(1)->get(); 
					foreach($getSite as $site){
						$site->increment('craw_replay',1); 
						$this->_site=$site; 
						echo $this->_site->domain;  
						$urlRank='http://data.alexa.com/data?cli=10&url='.$this->_site->domain; 
						$getXml=simplexml_load_file($urlRank); 
						if(!empty($getXml->SD->POPULARITY['TEXT'])){
							$this->_site->rank=$getXml->SD->POPULARITY['TEXT']; 
							//$this->_site->status='active'; 
						}
						if(!empty($getXml->SD->COUNTRY['CODE'])){
							$this->_site->country_code=$getXml->SD->COUNTRY['CODE']; 
							//$this->_site->status='active'; 
						}
						if(!empty($getXml->SD->COUNTRY['RANK'])){
							$this->_site->rank_country=$getXml->SD->COUNTRY['RANK']; 
							//$this->_site->status='active'; 
						}  
						try {
							$client = new Client([
								'headers' => [ 
									'Content-Type' => 'text/html',
									'User-Agent' => 'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n'
								], 
								'connect_timeout' => '2',
								'timeout' => '2'
							]);
							$url='http://'.$this->_site->domain; 
							$response = $client->request('GET', $url); 
							$getResponse=$response->getBody()->getContents(); 
							$dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse; 
							//dd($dataConvertUtf8); 
							$doc = new \DOMDocument;
							@$doc->loadHTML($dataConvertUtf8);   
							$nodes = $doc->getElementsByTagName('title');
							//get and display what you need:
							$title = $nodes->item(0)->nodeValue; 
							$metas = $doc->getElementsByTagName('meta');
							$keywords=''; 
							$description=''; 
							$image=''; 
							for ($i = 0; $i < $metas->length; $i++)
							{
								$meta = $metas->item($i);
								if($meta->getAttribute('name') == 'description')
									$description = $meta->getAttribute('content');
								if($meta->getAttribute('name') == 'keywords')
									$keywords = $meta->getAttribute('content');
								if($meta->getAttribute('property') == 'og:image')
									$image = $meta->getAttribute('content');
							}
							if(!empty($title)){
								$this->_site->domain_title=strip_tags($title,''); 
							}
							if(!empty($description)){
								$this->_site->domain_description=strip_tags($description,''); 
							}
							if(!empty($keywords)){
								$this->_site->domain_keywords=strip_tags($keywords,''); 
							}
							if(!empty($image)){
								$this->_site->domain_image=strip_tags($image,''); 
							}
							$h1tag = $doc->getElementsByTagName('h1'); 
							if($h1tag->length>0){
								$h1tagArray=array(); 
								foreach($h1tag as $h1){
									$h1tagArray[]=strip_tags(trim(preg_replace('/\s\s+/', ' ', $h1->nodeValue)),''); 
								} 
								if(!empty($h1tagArray)){
									$this->_domain_h1=array_unique($h1tagArray); 
								}
							}
							$h2tag = $doc->getElementsByTagName('h2'); 
							if($h2tag->length>0){
								$h2tagArray=array(); 
								foreach($h2tag as $h2){
									$h2tagArray[]=strip_tags(trim(preg_replace('/\s\s+/', ' ', $h2->nodeValue)),''); 
								} 
								if(!empty($h2tagArray)){
									$this->_domain_h2=array_unique($h2tagArray); 
								}
							}
							$h3tag = $doc->getElementsByTagName('h3'); 
							if($h3tag->length>0){
								$h3tagArray=array(); 
								foreach($h3tag as $h3){
									$h3tagArray[]=strip_tags(trim(preg_replace('/\s\s+/', ' ', $h3->nodeValue)),''); 
								} 
								if(!empty($h3tagArray)){
									$this->_domain_h3=array_unique($h3tagArray); 
								}
							}
							
							$h4tag = $doc->getElementsByTagName('h4'); 
							if($h4tag->length>0){
								$h4tagArray=array(); 
								foreach($h4tag as $h4){
									$h4tagArray[]=strip_tags(trim(preg_replace('/\s\s+/', ' ', $h4->nodeValue)),''); 
								} 
								if(!empty($h4tagArray)){
									$this->_domain_h4=array_unique($h4tagArray); 
								}
							}
							$atag = $doc->getElementsByTagName('a'); 
							if($atag->length>0){
								$atagArray=array(); 
								foreach($atag as $a){
									$atagArray[]=array(
									'text'=>strip_tags(trim(preg_replace('/\s\s+/', ' ', $a->nodeValue)),''), 
									'link'=>$a->getAttribute('href')
									); 
								} 
								if(!empty($atagArray)){
									$this->_domain_a=WebService::unique_multidim_array($atagArray,'text'); 
								}
							}
							$ipDomain=gethostbyname($this->_site->domain); 
							if(!empty($ipDomain)){
								$this->_domain_ip=$ipDomain; 
							}
							$status='true'; 
							if(!empty($title))
							{
								if(WebService::checkBlacklistWord($title, $this->_wordBlacklist)){
									$status='true';
								}else{
									$status='false';
								}
								
							}
							if(!empty($description))
							{
								if(WebService::checkBlacklistWord($description, $this->_wordBlacklist)){
									$status='true';
								}else{
									$status='false';
								}
							}
							if(!empty($keywords))
							{
								if(WebService::checkBlacklistWord($keywords, $this->_wordBlacklist)){
									$status='true';
								}else{
									$status='false';
								}
							}
							if($status=='true'){
								$this->_site->status='active'; 
							}else{
								$this->_site->status='blacklist'; 
							}
							$data=array(
								'h1'=>json_encode($this->_domain_h1), 
								'h2'=>json_encode($this->_domain_h2), 
								'h3'=>json_encode($this->_domain_h3), 
								'h4'=>json_encode($this->_domain_h4), 
								'a'=>json_encode($this->_domain_a), 
								'ip'=>$this->_domain_ip, 
								//'post_search'=>json_encode($this->_postSearch), 
								//'video_search'=>json_encode($this->_videoSearch), 
								'whois'=>$this->_domainWhois
							); 
							$domainData = Domain_data::firstOrNew(array('parent_id'=>$this->_site->id));
							$domainData->content=json_encode($data);
							$domainData->save();
							//Domain_info::where('parent_id',$this->_site->id)->update($data, ['upsert' => true]);
							$this->_site->save(); 
							//$this->_site=Domain::find($this->_site->id); 
							unset($client);
							//return $this->_site;
						}catch (\GuzzleHttp\Exception\ServerException $e){
							//$this->_site->status='delete'; 
							$this->_site->save(); 
							//$this->_site=Domain::find($this->_site->id); 
							//unset($client);
							//return $this->_site;
						}catch (\GuzzleHttp\Exception\BadResponseException $e){
							//$this->_site->status='delete'; 
							$this->_site->save(); 
							//$this->_site=Domain::find($this->_site->id); 
							//unset($client);
							//return $this->_site;
						}catch (\GuzzleHttp\Exception\ClientException $e){
							//$this->_site->status='delete'; 
							$this->_site->save(); 
							//$this->_site=Domain::find($this->_site->id); 
							//unset($client);
							//return $this->_site; 
						}catch (\GuzzleHttp\Exception\ConnectException $e){
							//$this->_site->status='delete'; 
							$this->_site->save(); 
							//$this->_site=Domain::find($this->_site->id); 
							//unset($client);
							//return $this->_site;
						}catch (\GuzzleHttp\Exception\RequestException $e){
							//$this->_site->status='delete'; 
							$this->_site->save(); 
							//$this->_site=Domain::find($this->_site->id); 
							//unset($client);
							//return $this->_site;
						}
						$this->_site->save(); 
						//$this->_site=Domain::find($this->_site->id); 
						//unset($client);
						dd($this->_site); 
					}
				}else{
					echo 'chưa hoạt động cron'; 
				}
			}
		}
		if($this->_parame['type']=='insert_keyword'){
			$cron_job=Cron_job::where('type','=','insert_keyword')->first();
			if(count($cron_job)>0) {
				$current_date = date('Y-m-d H:i:s');
				$datetime_start = new DateTime($cron_job->date_add_update);
				$datetime_end = new DateTime($current_date);
				$since_start = $datetime_start->diff($datetime_end);
				$minutes=$since_start->i;
				if($minutes>=0){
					$update_scron_job=Cron_job::where('type','=','insert_keyword')->update(array('date_add_update'=>Carbon::now()->format('Y-m-d H:i:s'))); 
					$getUrlReferer=Urlreferer::where('status','=','pending')->where('replay','<=',2)->limit(5)->get(); 
					$pslManager = new PublicSuffixListManager(); 
					$parser = new Parser($pslManager->getList()); 
					
					foreach($getUrlReferer as $url){
						$url->increment('replay',1); 
						$domainName = $parser->parseUrl($url->url); 
						if(!is_null($domainName->host->registerableDomain)){
							$fixDomain=explode('.',$domainName->host->registerableDomain);  
							if(!is_null($domainName->query) && $fixDomain[0]!='yandex'){
								parse_str($domainName->query, $query); 
								if($fixDomain[0]=='google' || $fixDomain[0]=='msn' || $fixDomain[0]=='bing' || $fixDomain[0]=='aol' || $fixDomain[0]=='ask'){ 
									if(!empty($query['q'])){
										if(WebService::is_valid_url($query['q'])!=true){
											$checkKeyword=Keywords::where('keyword_encode','=',base64_encode($query['q']))->first();
											if(empty($checkKeyword->id)){
												if(WebService::checkBlacklistWord($query['q'], $this->_wordBlacklist))
													{
														Keywords::insertGetId(array(
															'keyword'=>$query['q'], 
															'keyword_encode'=>base64_encode($query['q']), 
															'slug'=>Str::slug($query['q']), 
															'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
															'search'=>'pending', 
															'insert_by'=>'user_search',
														)); 
														$url->status='active'; 
														$url->save(); 
													}
											}else{
												$url->status='delete'; 
												$url->save(); 
											}
										}
									}
								}elseif($fixDomain[0]=='yahoo'){
									if(!empty($query['p'])){
										if(WebService::is_valid_url($query['p'])!=true){
											$checkKeyword=Keywords::where('keyword_encode','=',base64_encode($query['p']))->first(); 
											if(empty($checkKeyword->id)){
												if(WebService::checkBlacklistWord($query['p'], $this->_wordBlacklist))
													{
														Keywords::insertGetId(array(
															'keyword'=>$query['p'], 
															'keyword_encode'=>base64_encode($query['p']), 
															'slug'=>Str::slug($query['p']), 
															'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
															'search'=>'pending', 
															'insert_by'=>'user_search'
														)); 
														$url->status='active'; 
														$url->save(); 
													}
											}else{
												$url->status='delete'; 
												$url->save(); 
											}
										}
									}
								}
							}else{
								$url->status='faild'; 
								$url->save(); 
							}
						}else{
							$url->status='faild'; 
							$url->save(); 
						}
					}
				}
			}
		}
		if($this->_parame['type']=='send_comment'){
			$cron_job=Cron_job::where('type','=','send_comment')->first();
			if(count($cron_job)>0) {
				$current_date = date('Y-m-d H:i:s');
				$datetime_start = new DateTime($cron_job->date_add_update);
				$datetime_end = new DateTime($current_date);
				$since_start = $datetime_start->diff($datetime_end);
				$minutes=$since_start->i;
				if($minutes>=2){
					$update_scron_job=Cron_job::where('type','=','send_comment')->update(array('date_add_update'=>Carbon::now()->format('Y-m-d H:i:s'))); 
					$getComment=Comments::where('send','=','pending')->where('replay','<=',2)->limit(2)->get(); 
					foreach($getComment as $comment){
						$comment->increment('replay', 1); 
						$getPost=$comment->joinPost->post; 
						 if(!empty($comment->joinPost->post->id)){
							$getChannel=$comment->joinPost->post->postsJoinChannel->channel; 
							if(!empty($getChannel->joinEmail[0]->email->email_address)){ 
								if(!empty($comment->name->attribute_value)){
									$name=$comment->name->attribute_value; 
								}else{
									$name=''; 
								}
								if(!empty($comment->phone->attribute_value)){
									$phone=$comment->phone->attribute_value; 
								}else{
									$phone=''; 
								}
								if(!empty($comment->email->attribute_value)){
									$email=$comment->email->attribute_value; 
								}else{
									$email=''; 
								}
								$data=[
									'message_title'=>'Có bình luận mới trên '.$getChannel->channel_name, 
									'comment'=>$comment, 
									'channel'=>$getChannel, 
									'post'=>$getPost, 
									'name'=>$name, 
									'phone'=>$phone, 
									'email'=>$email
								]; 
								Mail::send('emails.sendComment', $data, function ($msg) use ($data) {
									$msg->from(config('app.app_email'),$data['channel']->channel_name);
									//$msg->replyTo($data['user_email'], $data['user_name']); 
									$msg->to($data['channel']->joinEmail[0]->email->email_address, $data['channel']->channel_name)
										->cc(config('app.mail_admin'))
										->subject($data['message_title']);
								});
								if(count(Mail::failures())>0){
								   $comment->send='faild'; 
								   $comment->save(); 
								}else{
									$comment->send='active'; 
									$comment->save(); 
								}
							}else{
								$comment->send='faild'; 
								$comment->save();  
							} 
						 }
					}
				}
			}
		}
		if($this->_parame['type']=='process_service'){
			$getProcess=Process_service::where('status','=','pending')->where('replay','<=',2)->get(); 
			foreach($getProcess as $process){
				$process->increment('replay', 1); 
				$item=json_decode($process->content); 
				if($process->type=='cloudReOrder'){
					$getCloud=Cloud::find($item->attributes->service_id); 
					if(!empty($getCloud->id)){
						$getCloud->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
						$getCloud->date_end=Carbon::parse($getCloud->date_end)->addMonth($item->quantity)->format('Y-m-d H:i:s'); 
						$getCloud->status='active'; 
						$getCloud->notify=0; 
						$getCloud->save(); 
						$contentMessage=[
							'cloud'=>$getCloud, 
						];
						$messageInsert=[
							'type'=>'cloudReOrder', 
							'from'=>$item->attributes->service_id, 
							'to'=>$item->attributes->author, 
							'message_title'=>'Gia hạn Cloud '.$getCloud->name.'-'.$item->attributes->service_attribute_name, 
							'message_body'=>json_encode($contentMessage), 
							'message_status'=>'unread', 
							'message_send'=>'pending', 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
						]; 
						Messages::create($messageInsert); 
						$process->status='active'; 
						$process->save();
					}else{
						$process->status='false'; 
						$process->save();
					}
				}else if($process->type=='hostingReOrder'){
					$getHosting=Hosting::find($item->attributes->service_id); 
					if(!empty($getHosting->id)){
						$getHosting->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
						$getHosting->date_end=Carbon::parse($getHosting->date_end)->addMonth($item->quantity)->format('Y-m-d H:i:s'); 
						$getHosting->status='active'; 
						$getHosting->notify=0; 
						$getHosting->save(); 
						$contentMessage=[
							'hosting'=>$getHosting, 
						];
						$messageInsert=[
							'type'=>'hostingReOrder', 
							'from'=>$item->attributes->service_id, 
							'to'=>$item->attributes->author, 
							'message_title'=>'Gia hạn Hosting '.$getHosting->name.'-'.$item->attributes->service_attribute_name, 
							'message_body'=>json_encode($contentMessage), 
							'message_status'=>'unread', 
							'message_send'=>'pending', 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
						]; 
						Messages::create($messageInsert); 
						$process->status='active';
						$process->save();
					}else{
						$process->status='faild'; 
						$process->save();
					}
				}else if($process->type=='hostingAdd'){
					if($item->attributes->service_id==26){
						$planName='host-1'; 
					}else if($item->attributes->service_id==27){
						$planName='host-2'; 
					}else if($item->attributes->service_id==28){
						$planName='host-3'; 
					}else if($item->attributes->service_id==29){
						$planName='host-4'; 
					}else if($item->attributes->service_id==30){
						$planName='host-5'; 
					}else if($item->attributes->service_id==31){
						$planName='host-6'; 
					}
					$pool = 'abcdefghijklmnopqrstuvwxyz'; 
					$poolPass = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*'; 
					$createDomain=time().".host.cungcap.net"; 
					$accountRandom=substr(str_shuffle(str_repeat($pool, 8)), 0, 8);
					$passRandom=substr(str_shuffle(str_repeat($poolPass, 8)), 0, 8).'@'; 
					$contentHost=array(
						'domain'=>$createDomain, 
						'userName'=>$accountRandom, 
						'password'=>$passRandom, 
						'url_login'=>'https://host.cungcap.net:2083'
					); 
					$insertHosting=Hosting::insertGetId(array(
						'name'=>$createDomain, 
						'type'=>'host_cungcap', 
						'content'=>json_encode($contentHost), 
						'user_id'=>$item->attributes->author, 
						'service_attribute_id'=>$item->attributes->service_id, 
						'status'=>'active', 
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'date_begin'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'date_end'=>Carbon::now()->addMonth($item->quantity)->format('Y-m-d H:i:s')
					)); 
					if($insertHosting){
						$getHosting=Hosting::find($insertHosting); 
						$contentMessage=[
							'hosting'=>$getHosting, 
						];
						$messageInsert=[
							'type'=>'hostingAdd', 
							'from'=>$item->attributes->service_id, 
							'to'=>$item->attributes->author, 
							'message_title'=>'Đăng ký hosting '.$getHosting->name, 
							'message_body'=>json_encode($contentMessage), 
							'message_status'=>'unread', 
							'message_send'=>'pending', 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
						]; 
						Messages::create($messageInsert); 
						$process->status='active';
						$process->save();
						$cpanel = new \Gufy\CpanelPhp\Cpanel([
							  'host'        =>  'https://host.cungcap.net:2087', 
							  'username'    =>  'root', 
							  'auth_type'   =>  'hash', 
							  'password'    =>  'NE1FIY5EDECUIC1OZRNI0ZGG6JRP04CG',
						]);
						$accounts = $cpanel->createAccount($createDomain, $accountRandom, $passRandom, $planName); 
						echo $accounts; 
					}else{
						$process->status='faild';
						$process->save();
					}
				}else if($process->type=='cloudAdd'){
					$insertCloud=Cloud::insertGetId(array(
						'name'=>$item->attributes->service_attribute_name.'-'.Carbon::now()->format('d-m-Y'), 
						'type'=>'outsite', 
						'user_id'=>$item->attributes->author, 
						'service_attribute_id'=>$item->attributes->service_id, 
						'status'=>'active', 
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'date_begin'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'date_end'=>Carbon::now()->addMonth($item->quantity)->format('Y-m-d H:i:s')
					)); 
					if($insertCloud){
						$getCloud=Cloud::find($insertCloud); 
						$contentMessage=[
							'cloud'=>$getCloud, 
						];
						$messageInsert=[
							'type'=>'cloudAdd', 
							'from'=>$item->attributes->service_id, 
							'to'=>$item->attributes->author, 
							'message_title'=>'Đăng ký cloud '.$getCloud->name, 
							'message_body'=>json_encode($contentMessage), 
							'message_status'=>'unread', 
							'message_send'=>'pending', 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
						]; 
						Messages::create($messageInsert); 
						$process->status='active';
						$process->save();
					}else{
						$process->status='faild';
						$process->save();
					}
				}else if($process->type=='emailAdd'){
					$insertEmailServer=Mail_server::insertGetId(array(
						'name'=>$item->attributes->service_attribute_name.'-'.Carbon::now()->format('d-m-Y'), 
						'type'=>'outsite', 
						'user_id'=>$item->attributes->author, 
						'service_attribute_id'=>$item->attributes->service_id, 
						'status'=>'active', 
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'date_begin'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'date_end'=>Carbon::now()->addMonth($item->quantity)->format('Y-m-d H:i:s')
					)); 
					if($insertEmailServer){
						$getEmail=Mail_server::find($insertEmailServer); 
						$contentMessage=[
							'email'=>$getEmail, 
						];
						$messageInsert=[
							'type'=>'emailAdd', 
							'from'=>$item->attributes->service_id, 
							'to'=>$item->attributes->author, 
							'message_title'=>'Đăng ký Email Server '.$getCloud->name, 
							'message_body'=>json_encode($contentMessage), 
							'message_status'=>'unread', 
							'message_send'=>'pending', 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
						]; 
						Messages::create($messageInsert); 
						$process->status='active';
						$process->save();
					}else{
						$process->status='faild';
						$process->save();
					}
				}else if($process->type=='domainAddCart'){ 
					$getDomain=Domain::where('domain','=',$item->name)->first(); 
					if(empty($getDomain->id)){
						$newDomain=new Domain(); 
						$newDomain->domain=$item->name;  
						$newDomain->domain_encode=base64_encode($item->name); 
						$newDomain->created_at=Carbon::now()->format('Y-m-d H:i:s'); 
						$newDomain->save(); 
						if(!empty($newDomain->id)){
							$getDomain=Domain::find($newDomain->id); 
						}else{
							return 'Lỗi không thêm được tên miền mới vào cơ sở dữ liệu';
						}
					}
					if(!empty($getDomain->id)){
						$getDomain->domain_primary='default'; 
						$getDomain->domain_location='register'; 
						$getDomain->service_attribute_id=$item->attributes->service_id; 
						$getDomain->user_id=$item->attributes->author; 
						$getDomain->status='active'; 
						$getDomain->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
						$getDomain->date_end=Carbon::parse(Carbon::now()->format('Y-m-d H:i:s'))->addMonth($item->quantity*12)->format('Y-m-d H:i:s'); 
						$getDomain->save(); 
						$getDomain=Domain::find($getDomain->id); 
						$client = new Client();
						$userData='{
							"email": "'.config("app.inet_email").'",
							"password": "'.config("app.inet_pass").'"
						}';
						$signIn = $client->request('POST', 'https://dms.inet.vn/api/sso/v1/user/signin',
							[
								'headers' => [ 'Content-Type' => 'application/json' ], 
								'body' => $userData
							 ]
						);
						$responseSignIn=json_decode($signIn->getBody()->getContents()); 
						if($signIn->getStatusCode()=='200'){
							
							if($getDomain->service_attribute_id==6 || $getDomain->service_attribute_id==7 || $getDomain->service_attribute_id==22){
								$registrar='inet'; 
							}else{
								$registrar='inet-global'; 
							}
							$words = explode(" ",$item->attributes->customerCity);
							$Province = "";
							foreach ($words as $w) {
							  $Province .= $w[0];
							}
							$postData='{
							   "token": "'.$responseSignIn->session->token.'",
							   "name": "'.$getDomain->domain.'",
							   "period": '.$item->quantity.',
							   "customerId": 266038,
							   "registrar": "'.$registrar.'",
							   "nsList": [
								  {
									 "hostname": "ns1.inet.vn"
								  },
								  {
									 "hostname": "ns2.inet.vn"
								  }
							   ],
							   "contacts": [
								  {
									 "fullname": "Công ty cổ phần Cung Cấp",
									 "organization": true,
									 "email": "contact@cungcap.net",
									 "country": "VN",
									 "province": "HCM",
									 "address": "104 Hoang Dieu 2, P. Linh Chieu, Q. Thu Duc",
									 "phone": "0903706288",
									 "fax": "0903706288",
									 "type": "registrant",
									 "dataExtend": "{\"gender\":\"male\",\"idNumber\":\"0314609089\",\"birthday\":\"05/09/2017\"}"
								  },
								  {
									 "fullname": "'.$item->attributes->customerName.'",
									 "organization": false,
									 "email": "'.$item->attributes->customerEmail.'",
									 "country": "'.$item->attributes->customerCountry.'",
									 "province": "'.$Province.'",
									 "address": "'.$item->attributes->customerAddress.'",
									 "phone": "'.$item->attributes->customerPhone.'",
									 "fax": "'.$item->attributes->customerPhone.'",
									 "type": "admin",
									 "dataExtend": "{\"gender\":\"'.$item->attributes->customerSex.'\",\"idNumber\":\"0314609089\",\"birthday\":\"'.$item->attributes->customerBirthday.'\"}"
								  },
								  {
									 "fullname": "'.$item->attributes->customerName.'",
									 "email": "'.$item->attributes->customerEmail.'",
									 "country": "'.$item->attributes->customerCountry.'",
									 "province": "'.$Province.'",
									 "address": "'.$item->attributes->customerAddress.'",
									 "phone": "'.$item->attributes->customerPhone.'",
									 "fax": "'.$item->attributes->customerPhone.'",
									 "type": "technique",
									 "dataExtend": "{\"gender\":\"'.$item->attributes->customerSex.'\",\"idNumber\":\"0314609089\",\"birthday\":\"'.$item->attributes->customerBirthday.'\"}"
								  },
								  {
									 "fullname": "'.$item->attributes->customerName.'",
									 "email": "'.$item->attributes->customerEmail.'",
									 "country": "'.$item->attributes->customerCountry.'",
									 "province": "'.$Province.'",
									 "address": "'.$item->attributes->customerAddress.'",
									 "phone": "'.$item->attributes->customerPhone.'",
									 "fax": "'.$item->attributes->customerPhone.'",
									 "type": "billing",
									 "dataExtend": "{\"gender\":\"'.$item->attributes->customerSex.'\",\"idNumber\":\"0314609089\",\"birthday\":\"'.$item->attributes->customerBirthday.'\"}"
								  }
							   ]
							}';  
							$response = $client->request('POST', 'https://dms.inet.vn/api/rms/v1/domain/create',
								[
									'headers' => [ 'Content-Type' => 'application/json','token' => $responseSignIn->session->token], 
									'body' => $postData
								]
							);
							$responseDecode=json_decode($response->getBody()->getContents()); 
							if($responseDecode->status=='active'){
								$getDomain->status='active'; 
								$getDomain->registrar='inet'; 
								$getDomain->domain_id_registrar=$responseDecode->id; 
								$getDomain->domain_detail=$response->getBody()->getContents(); 
								$getDomain->save(); 
								$postUpdateRecord='{
								   "token": "'.$responseSignIn->session->token.'",
								   "id": '.$responseDecode->id.',
								   "recordList": [
									  {
										 "type": "A",
										 "name": "@",
										 "data": "'.config('app.ip_address').'",
										 "action": "add"
									  },
									  {
										 "type": "A",
										 "name": "*",
										 "data": "'.config('app.ip_address').'",
										 "action": "add"
									  }
								   ]
								}'; 
								$response = $client->request('POST', 'https://dms.inet.vn/api/rms/v1/domain/updaterecord',
									[
										'headers' => [ 'Content-Type' => 'application/json','token' => $responseSignIn->session->token], 
										'body' => $postUpdateRecord
									]
								);
								$getDomainNew=Domain::find($getDomain->id); 
								$contentMessage=[
									'domain'=>$getDomainNew
								];
								$messageInsert=[
									'type'=>'domainAddCart', 
									'from'=>0, 
									'to'=>$item->attributes->author, 
									'message_title'=>'Đăng ký tên miền '.$getDomainNew->domain, 
									'message_body'=>json_encode($contentMessage), 
									'message_status'=>'unread', 
									'message_send'=>'pending', 
									'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
									'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
								]; 
								Messages::create($messageInsert); 
								$process->status='active'; 
								$process->save();
							}else{
								dd($response->getBody()->getContents()); 
							}
						}else{
							dd($signIn->getBody()->getContents()); 
						}
					}else{
						return 'Không tìm thấy tên miền! '; 
					}
				}else if($process->type=='domainAdd'){
					$getDomain=Domain::find($item->attributes->domain_id); 
					$user=User::find($item->attributes->author); 
					if(!empty($getDomain->id)){
						$client = new Client();
						$userData='{
							"email": "'.config("app.inet_email").'",
							"password": "'.config("app.inet_pass").'"
						}';
						$signIn = $client->request('POST', 'https://dms.inet.vn/api/sso/v1/user/signin',
							[
								'headers' => [ 'Content-Type' => 'application/json' ], 
								'body' => $userData
							 ]
						);
						$responseSignIn=json_decode($signIn->getBody()->getContents()); 
						if($signIn->getStatusCode()=='200'){
							if($getDomain->service_attribute_id==6 || $getDomain->service_attribute_id==7 || $getDomain->service_attribute_id==22){
								$registrar='inet'; 
							}else{
								$registrar='inet-global'; 
							}
							$postData='{
							   "token": "'.$responseSignIn->session->token.'",
							   "name": "'.$getDomain->domain.'",
							   "period": 1,
							   "customerId": 266038,
							   "registrar": "'.$registrar.'",
							   "nsList": [
								  {
									 "hostname": "ns1.inet.vn"
								  },
								  {
									 "hostname": "ns2.inet.vn"
								  }
							   ],
							   "contacts": [
								  {
									 "fullname": "Công ty cổ phần Cung Cấp",
									 "organization": true,
									 "email": "contact@cungcap.net",
									 "country": "VN",
									 "province": "HCM",
									 "address": "104 Hoang Dieu 2, P. Linh Chieu, Q. Thu Duc",
									 "phone": "0903706288",
									 "fax": "0903706288",
									 "type": "registrant",
									 "dataExtend": "{\"gender\":\"male\",\"idNumber\":\"0314609089\",\"birthday\":\"05/09/2017\"}"
								  },
								  {
									 "fullname": "Nguyễn Hùng Thanh",
									 "organization": false,
									 "email": "thanh@cdv.vn",
									 "country": "VN",
									 "province": "HCM",
									 "address": "104 Hoang Dieu 2, P. Linh Chieu, Q. Thu Duc",
									 "phone": "0903706288",
									 "fax": "0903706288",
									 "type": "admin",
									 "dataExtend": "{\"gender\":\"male\",\"idNumber\":\"0314609089\",\"birthday\":\"05/09/2017\"}"
								  },
								  {
									 "fullname": "Nguyễn Hùng Thanh",
									 "email": "thanh@cdv.vn",
									 "country": "VN",
									 "province": "HCM",
									 "address": "104 Hoang Dieu 2, P. Linh Chieu, Q. Thu Duc",
									 "phone": "0903706288",
									 "fax": "0903706288",
									 "type": "technique",
									 "dataExtend": "{\"gender\":\"male\",\"idNumber\":\"0314609089\",\"birthday\":\"05/09/2017\"}"
								  },
								  {
									 "fullname": "Nguyễn Hùng Thanh",
									 "email": "thanh@cdv.vn",
									 "country": "VN",
									 "province": "HCM",
									 "address": "104 Hoang Dieu 2, P. Linh Chieu, Q. Thu Duc",
									 "phone": "0903706288",
									 "fax": "0903706288",
									 "type": "billing",
									 "dataExtend": "{\"gender\":\"male\",\"idNumber\":\"0314609089\",\"birthday\":\"05/09/2017\"}"
								  }
							   ]
							}';  
							$response = $client->request('POST', 'https://dms.inet.vn/api/rms/v1/domain/create',
								[
									'headers' => [ 'Content-Type' => 'application/json','token' => $responseSignIn->session->token], 
									'body' => $postData
								]
							);
							$responseDecode=json_decode($response->getBody()->getContents()); 
							if($responseDecode->status=='active'){
								$getDomain->status='active'; 
								$getDomain->registrar='inet'; 
								$getDomain->domain_id_registrar=$responseDecode->id; 
								$getDomain->domain_detail=$response->getBody()->getContents(); 
								$getDomain->created_at=Carbon::now()->format('Y-m-d H:i:s'); 
								$getDomain->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
								$getDomain->date_begin=Carbon::now()->format('Y-m-d H:i:s');
								$getDomain->date_end=Carbon::parse(Carbon::now()->format('Y-m-d H:i:s'))->addMonth($item->quantity*12)->format('Y-m-d H:i:s');
								$getDomain->save(); 
								$postUpdateRecord='{
								   "token": "'.$responseSignIn->session->token.'",
								   "id": '.$responseDecode->id.',
								   "recordList": [
									  {
										 "type": "A",
										 "name": "@",
										 "data": "'.config('app.ip_address').'",
										 "action": "add"
									  },
									  {
										 "type": "A",
										 "name": "*",
										 "data": "'.config('app.ip_address').'",
										 "action": "add"
									  }
								   ]
								}'; 
								$response = $client->request('POST', 'https://dms.inet.vn/api/rms/v1/domain/updaterecord',
									[
										'headers' => [ 'Content-Type' => 'application/json','token' => $responseSignIn->session->token], 
										'body' => $postUpdateRecord
									]
								);
								$getDomainNew=Domain::find($getDomain->id); 
								$contentMessage=[
									'domain'=>$getDomainNew
								];
								$messageInsert=[
									'type'=>'domainAdd', 
									'from'=>$this->_channel->id, 
									'to'=>$item->attributes->author, 
									'message_title'=>'Đăng ký tên miền '.$getDomainNew->domain, 
									'message_body'=>json_encode($contentMessage), 
									'message_status'=>'unread', 
									'message_send'=>'pending', 
									'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
									'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
								]; 
								Messages::create($messageInsert); 
								$process->status='active'; 
								$process->save();
							}else{
								$process->status='faild';
								$process->save();
							}
						}else{
							$process->status='faild';
							$process->save();
						}
					}else{
						$process->status='faild';
						$process->save();
					}
				}
			}
		}
		if($this->_parame['type']=='reorder_service'){
			$getHosting=Hosting::where('type','=','inet')->where('status','=','active')->where('date_end_provided','<=',Carbon::now()->addDay(20)->format('Y-m-d H:i:s'))->get();  
			if(count($getHosting)>0){
				$client = new Client();
				$userData='{
					"email": "'.config("app.inet_email").'",
					"password": "'.config("app.inet_pass").'"
				}';
				$signIn = $client->request('POST', 'https://dms.inet.vn/api/sso/v1/user/signin',
					[
						'headers' => [ 'Content-Type' => 'application/json' ], 
						'body' => $userData
					 ]
				);
				$responseSignIn=json_decode($signIn->getBody()->getContents()); 
				$signInCode=$signIn->getStatusCode(); 
				if($signInCode=='200' && $responseSignIn->status=='SUCCESS'){
					foreach($getHosting as $hosting){
						if(Carbon::parse($hosting->date_end)->format('Y-m-d H:i:s') > Carbon::now()->format('Y-m-d H:i:s')){
							$dataHosting=json_decode($hosting->content); 
							$postData='{
							   "id": '.$dataHosting->id.',
							   "period": 1,
							   "expireDate": "'.Carbon::parse($dataHosting->expireDate)->format('Y-m-d h:i').'",
							}';
							$response = $client->request('POST', 'https://dms.inet.vn/api/rms/v1/hosting/renew',
								[
									'headers' => [ 'Content-Type' => 'application/json','token' => $responseSignIn->session->token], 
									'body' => $postData
								]
							); 
							$getResponse=$response->getBody()->getContents(); 
							$responseDecode=json_decode($getResponse); 
							echo $getResponse; exit;
							if($response->getStatusCode()=='200'){
								$hosting->content=$getResponse; 
								$hosting->date_end_provided=Carbon::parse($responseDecode->expireDate)->format('Y-m-d H:i:s'); 
								$hosting->save(); 
								$messageInsert=[
									'type'=>'hostingReorderInet', 
									'from'=>0, 
									'to'=>0, 
									'message_title'=>'Gia hạn hosting Inet '.$getHosting->name, 
									'message_body'=>'', 
									'message_status'=>'unread', 
									'message_send'=>'pending', 
									'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
									'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
								]; 
								Messages::create($messageInsert); 
							}
						}
					}
				}
			}
		}
		if($this->_parame['type']=='delete_history'){
			$getHistory=History::all(); 
			foreach($getHistory as $history){
				if($history->history_type=='posts_view' && Carbon::parse($history->created_at)->addDay(1)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
					$history->delete(); 
				}
				if($history->history_type=='channel_view' && Carbon::parse($history->created_at)->addDay(1)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
					$history->delete(); 
				}
				if($history->history_type=='register_add' && Carbon::parse($history->created_at)->addDay(1)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
					$history->delete(); 
				}
				if($history->history_type=='comment_add' && Carbon::parse($history->created_at)->addDay(1)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
					$history->delete(); 
				}
			}
		}
	}	
}
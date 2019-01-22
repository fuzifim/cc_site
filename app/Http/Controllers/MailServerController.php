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
use App\Model\Mail_server;
use App\Model\Mail_server_join;
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
class MailServerController extends ConstructController
{

	public function __construct(){
		parent::__construct(); 
	}
	public function mail_serverSaveId()
    {
		if($this->_security==true){
			$mail_serverId=Input::get('mail_serverId'); 
			$date_begin=Input::get('date_begin'); 
			$date_end=Input::get('date_end'); 
			$mail_serverStatus=Input::get('mail_serverStatus'); 
			$getMail_server=Mail_server::find($mail_serverId); 
			if(!empty($getMail_server->name) && !empty($date_begin) && !empty($date_end)){
				$getMail_server->date_begin=Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_begin))->format('Y-m-d H:i:s'); 
				$getMail_server->date_end=Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_end))->format('Y-m-d H:i:s'); 
				$getMail_server->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
				$getMail_server->status=$mail_serverStatus; 
				$getMail_server->save(); 
				return response()->json(['success'=>true,
					'message'=>'Thông tin đã được lưu ', 
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'không tìm thấy mail_server'
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
	public function mail_serverGetId()
    {
		$mail_serverId=Input::get('mail_serverId'); 
		$getMail_server=Mail_server::find($mail_serverId); 
		if(!empty($getMail_server->name)){
			return response()->json(['success'=>true,
				'message'=>'Thông tin mail_server', 
				'getMail_server'=>$getMail_server, 
				'date_begin'=>Carbon::parse($getMail_server->date_begin)->format('d-m-Y'), 
				'date_end'=>Carbon::parse($getMail_server->date_end)->format('d-m-Y'), 
			]);
		}else{
			return response()->json(['success'=>false,
				'message'=>'không tìm thấy mail_server'
			]);
		}
	}
	public function mail_serverRemove(){
		if($this->_security==true){
			$mail_serverId=Input::get('mail_serverId'); 
			Mail_server::find($mail_serverId)->delete(); 
			return response()->json(['success'=>true, 
				'message'=>'Đã xóa dịch vụ mail_server', 
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function mail_serverAdd(){
		if($this->_security==true){
			$mail_serverName=Input::get('mail_serverName'); 
			$serviceAttributeId=Input::get('serviceAttributeId'); 
			$date_begin=Input::get('date_begin'); 
			$date_end=Input::get('date_end'); 
			$mail_serverId=Mail_server::insertGetId(array(
				'name'=>$mail_serverName, 
				'type'=>'outsite', 
				'service_attribute_id'=>$serviceAttributeId, 
				'status'=>'active', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'date_begin'=>Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_begin))->format('Y-m-d H:i:s'), 
				'date_end'=>Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_end))->format('Y-m-d H:i:s')
			)); 
			if(isset($mail_serverId)){
				Mail_server_join::insertGetId(array(
					'table'=>'channel', 
					'table_parent_id'=>$this->_channel->id, 
					'mail_server_parent_id'=>$mail_serverId, 
					'status'=>'active', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				return response()->json(['success'=>true, 
					'message'=>'Đã thêm dịch vụ mail_server', 
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
	public function mail_serverList()
    {
		if(Auth::check()){
			$getMailServer=Mail_server::where('user_id','=',Auth::user()->id)->paginate(8); 
			$view = array(
				'getMailServer'=>$getMailServer
			);
			return $this->_theme->scope('mailserver.list', $view)->render(); 
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	
}
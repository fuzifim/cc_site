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
use App\Model\Cloud;
use App\Model\Cloud_join;
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
use DigitalOceanV2\Adapter\BuzzAdapter;
use DigitalOceanV2\DigitalOceanV2;
class CloudController extends ConstructController
{
	public function __construct(){
		parent::__construct(); 
	}
	public function cloudShow()
	{
		$adapter = new BuzzAdapter(config('app.DigitaloceanAccessTokens'));
		$digitalocean = new DigitalOceanV2($adapter); 
		$size = $digitalocean->size();
		$sizes = $size->getAll(); 
		dd($sizes); 
	}
	public function cloudSaveId()
    {
		if($this->_security==true){
			$cloudId=Input::get('cloudId'); 
			$date_begin=Input::get('date_begin'); 
			$date_end=Input::get('date_end'); 
			$cloudStatus=Input::get('cloudStatus'); 
			$getcloud=Cloud::find($cloudId); 
			if(!empty($getcloud->name) && !empty($date_begin) && !empty($date_end)){
				$getcloud->date_begin=Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_begin))->format('Y-m-d H:i:s'); 
				$getcloud->date_end=Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_end))->format('Y-m-d H:i:s'); 
				$getcloud->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
				$getcloud->status=$cloudStatus; 
				$getcloud->save(); 
				return response()->json(['success'=>true,
					'message'=>'Thông tin đã được lưu ', 
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'không tìm thấy cloud'
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
	public function cloudGetId()
    {
		$cloudId=Input::get('cloudId'); 
		$getcloud=Cloud::find($cloudId); 
		if(!empty($getcloud->name)){
			return response()->json(['success'=>true,
				'message'=>'Thông tin cloud', 
				'getcloud'=>$getcloud, 
				'date_begin'=>Carbon::parse($getcloud->date_begin)->format('d-m-Y'), 
				'date_end'=>Carbon::parse($getcloud->date_end)->format('d-m-Y'), 
			]);
		}else{
			return response()->json(['success'=>false,
				'message'=>'không tìm thấy cloud'
			]);
		}
	}
	public function cloudRemove(){
		if($this->_security==true){
			$cloudId=Input::get('cloudId'); 
			Cloud::find($cloudId)->delete(); 
			return response()->json(['success'=>true, 
				'message'=>'Đã xóa dịch vụ cloud', 
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function cloudAdd(){
		if($this->_security==true){
			$cloudName=Input::get('cloudName'); 
			$serviceAttributeId=Input::get('serviceAttributeId'); 
			$date_begin=Input::get('date_begin'); 
			$date_end=Input::get('date_end'); 
			$cloudId=Cloud::insertGetId(array(
				'name'=>$cloudName, 
				'type'=>'outsite', 
				'service_attribute_id'=>$serviceAttributeId, 
				'status'=>'active', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'date_begin'=>Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_begin))->format('Y-m-d H:i:s'), 
				'date_end'=>Carbon::parse(Carbon::createFromFormat('d-m-Y', $date_end))->format('Y-m-d H:i:s')
			)); 
			if(isset($cloudId)){
				Cloud_join::insertGetId(array(
					'table'=>'channel', 
					'table_parent_id'=>$this->_channel->id, 
					'cloud_parent_id'=>$cloudId, 
					'status'=>'active', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				return response()->json(['success'=>true, 
					'message'=>'Đã thêm dịch vụ cloud', 
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
	public function cloudList()
    {
		if(Auth::check()){
			$getCloud=Cloud::where('user_id','=',Auth::user()->id)->paginate(8);
			$view = array(
				'getCloud'=>$getCloud
			);
			return $this->_theme->scope('cloud.list', $view)->render(); 
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	
}
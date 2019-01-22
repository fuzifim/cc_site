<?php 
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request; 
use Illuminate\Support\Str;
use Auth;
use App\User; 
use App\Model\History; 
use App\Model\Category; 
use App\Model\Category_join;
use App\Model\Category_join_channel;
use App\Model\Keywords;
use App\Model\Posts;
use App\Model\Posts_join; 
use App\Model\Posts_join_channel;
use App\Model\Posts_join_category;
use App\Model\Posts_attribute; 
use App\Model\Posts_join_keywords;
use App\Model\Slug; 
use App\Model\Media; 
use App\Model\Media_join;
use App\Model\Regions;
use App\Model\Subregions;
use App\Model\Region_district;
use App\Model\Region_ward;
use App\Model\Domain; 
use App\Model\Domain_join; 
use App\Model\Channel; 
use App\Model\Channel_join; 
use App\Model\Channel_join_region;
use App\Model\Channel_join_subregion;
use App\Model\Channel_join_district;
use App\Model\Channel_join_ward;
use App\Model\Channel_join_field; 
use App\Model\Channel_join_address; 
use App\Model\Channel_join_email; 
use App\Model\Channel_join_phone; 
use App\Model\Channel_attribute; 
use App\Model\Media_join_post; 
use App\Model\Media_join_channel; 
use App\Model\Address;
use App\Model\Address_join_region;
use App\Model\Address_join_subregion;
use App\Model\Address_join_district;
use App\Model\Address_join_ward;
use App\Model\Email;
use App\Model\Email_join;
use App\Model\Phone;
use App\Model\Phone_join; 
use App\Model\Company; 
use App\Model\Company_join; 
use App\Model\Company_join_channel; 
use App\Model\Company_join_channel_parent; 
use App\Model\Company_join_field; 
use App\Model\Company_join_address; 
use App\Model\Fields; 
use App\Model\Node;
use Carbon\Carbon; 
use WebService;
use DB;
use Response;
use Session;
use Redirect; 
use Input; 
use Lang; 
use Cache; 
use App\Model\SiteLink;
class CompanyController extends ConstructController
{
	public function __construct(){
		parent::__construct(); 
	}
	public function companyViewSlug(Request $request)
    {
		if($this->_channel->channel_parent_id==0){
			if(!empty($this->_parame['slug'])){
				$slug=explode('-',$this->_parame['slug']); 
				//$getCompany=Company::find($slug[0]); 
				
				return Redirect::to('https://com-'.$slug[0].'.'.config('app.url'), 301);
			}else{
				$page = $request->has('page') ? $request->query('page') : 1;
				$getCompany = Cache::store('file')->remember('getNewCompanyUpdate_'.$page,5, function()
					{
						return Node::where('type','company')->where('status','!=','delete')
							->orderBy('updated_at','desc')
							->paginate(10); 
					}); 
				$getNewDomainUpdate = Cache::store('file')->remember('getNewDomain'.$page, 5, function()
				{
					return Node::where('type','domain')->where('status','=','active')
						->orderBy('updated_at','desc')
						->paginate(20); 
				}); 
				$Keywords=array(); 
				$return=array(
					'companyList'=>$getCompany,
					'getSite'=>$getNewDomainUpdate, 
					'Keywords'=>$Keywords, 
					'getField'=>''
				); 
				return $this->_theme->scope('company.region', $return)->render(); 
			}
		}
	}
	public function saveCompany()
    {
		$idCompany=Input::get('idCompany'); 
		$companyName=Input::get('companyName'); 
		$insert=false; 
		if(!empty($idCompany)){
			$getCompany=Company::find($idCompany); 
			if(!empty($getCompany->id)){
				if(!empty($companyName) && $companyName!=$getCompany->company_name){
					$getCompany->company_name=$companyName; 
					$getCompany->save(); 
				}
				if(!empty($this->_channel->channelParent->id)){
					Company_join_channel_parent::where('channel_parent_id','=',$this->_channel->channelParent->id)->where('company_id','=',$getCompany->id)->delete(); 
					Company_join_channel_parent::insertGetId(array(
						'channel_parent_id'=>$this->_channel->channelParent->id, 
						'company_id'=>$getCompany->id,
					));
				}
				Company_join_channel::where('channel_id','=',$this->_channel->id)->delete(); 
				Company_join_channel::insertGetId(array(
					'channel_id'=>$this->_channel->id, 
					'company_id'=>$getCompany->id,
				)); 
				return response()->json(['success'=>true,
					'message'=>'Cập nhật thông tin công ty thành công. ', 
				]);
			}else{
				$insert=true; 
			}
		}else{
			$insert=true; 
		}
		if($insert==true){
			if(!empty($companyName)){
				$getCompany=Company::where('company_name','=',$companyName)->first(); 
				if(empty($getCompany)){
					$idCompany=Company::insertGetId(array(
						'company_name'=>htmlspecialchars(strip_tags($companyName, '')), 
						'company_created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'company_updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'company_status'=>'pending'
					)); 
					if(!empty($idCompany)){
						if(!empty($this->_channel->channelParent->id)){
							Company_join_channel_parent::where('channel_parent_id','=',$this->_channel->channelParent->id)->where('company_id','=',$idCompany)->delete(); 
							Company_join_channel_parent::insertGetId(array(
								'channel_parent_id'=>$this->_channel->channelParent->id, 
								'company_id'=>$idCompany,
							));
						}
						Company_join_channel::insertGetId(array(
							'channel_id'=>$this->_channel->id, 
							'company_id'=>$idCompany,
						)); 
						if(!empty($this->_channel->joinAddress[0]->address->address)){
							Company_join_address::insertGetId(array(
								'company_id'=>$idCompany, 
								'address_id'=>$this->_channel->joinAddress[0]->address->id
							)); 
						}
						return response()->json(['success'=>true,
							'message'=>'Thêm công ty mới thành công. ', 
						]);
					}
				}else{
					return response()->json(['success'=>false,
						'message'=>'Không thể thêm công ty mới! ', 
					]);
				}
			}else{
				return response()->json(['success'=>false,
					'message'=>'Chưa cập nhật tên công ty! ', 
				]);
			}
		}
	}
	public function companyChannelDelete(){
		Company_join_channel::where('channel_id','=',$this->_channel->id)->delete(); 
		return response()->json(['success'=>true,
			'message'=>'Đã xóa công ty trên '.$this->_channel->channel_name, 
		]);
	}
}
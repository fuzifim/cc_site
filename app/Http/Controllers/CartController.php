<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Request;
use Illuminate\Support\Str;
use Auth;
use App\User;
use App\Model\Users_join; 
use App\Model\Users_attribute;  
use App\Permission;
use App\Role;
use Carbon\Carbon;
use App\Model\Regions;
use App\Model\Subregions;
use App\Model\History; 
use App\Model\Messages; 
use App\Model\Channel; 
use App\Model\Channel_join;
use App\Model\Channel_attribute; 
use App\Model\Channel_role;
use App\Model\Domain;
use App\Model\Domain_join;
use App\Model\Domain_attribute;
use App\Model\Hosting;
use App\Model\Hosting_join;
use App\Model\Cloud;
use App\Model\Cloud_join;
use App\Model\Mail_server;
use App\Model\Mail_server_join;
use App\Model\Cart_order;
use App\Model\Cart_order_attribute;
use App\Model\Cart_order_join; 
use App\Model\Posts; 
use App\Model\Posts_join_channel; 
use App\Model\Services; 
use App\Model\Services_attribute; 
use App\Model\Voucher; 
use App\Model\Voucher_attribute; 
use App\Model\Voucher_join; 
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use File;
use Theme;
use WebService; 
use Site;
use Route; 
use Redirect; 
use Response; 
use Input; 
use Validator; 
use Hash; 
use Cart; 
use Session; 
use App\Http\Controllers\DomainController;
class CartController extends ConstructController
{
	public function __construct(){
		parent::__construct(); 
	}
	/*--new--*/
	public function buyNow()
	{
		$itemId=Input::get('itemId'); 
		$getPost=Posts::find($itemId); 
		if(!empty($getPost->id)){
			if(Auth::check()){
				$user=Auth::user(); 
				$userName=$user->name; 
				$userEmail=$user->email; 
				$userPhone=$user->phone; 
				$userAddress=''; 
				
			}else{
				if(Session::has('buyNow')){
					$getSession=Session::get('buyNow'); 
					if(!empty($getSession['userName'])){
						$userName=$getSession['userName']; 
					}else{
						$userName=''; 
					}
					if(!empty($getSession['userEmail'])){
						$userEmail=$getSession['userEmail']; 
					}else{
						$userEmail=''; 
					}
					if(!empty($getSession['userPhone'])){
						$userPhone=$getSession['userPhone']; 
					}else{
						$userPhone=''; 
					}
					if(!empty($getSession['userAddress'])){
						$userAddress=$getSession['userAddress']; 
					}else{
						$userAddress=''; 
					}
				}else{
					$userName=''; 
					$userEmail=''; 
					$userPhone=''; 
					$userAddress=''; 
				}
			}
			Session::put('buyNow', [ 
				'itemId'=>$getPost->id, 
				'itemName'=>$getPost->posts_title, 
				'itemPrice'=>$getPost->price->posts_attribute_value, 
				'link'=>route('channel.slug',array($this->_domain->domain,$getPost->getSlug->slug_value)), 
				'userName' => $userName, 
				'userEmail' => $userEmail, 
				'userPhone' => $userPhone, 
				'userAddress' => $userAddress, 
				'buyQuanlity'=>1
			]); 
			return response()->json(['success'=>true, 
				'dataBuyNow'=>Session::get('buyNow'), 
			]);
		}
	}
	public function buyNowSend()
	{
		$userName=Input::get('userName'); 
		$userEmail=Input::get('userEmail'); 
		$userPhone=Input::get('userPhone'); 
		$userAddress=Input::get('userAddress'); 
		$buyQuanlity=Input::get('buyQuanlity'); 
		$content=Input::get('content'); 
		$error=''; 
		if(count($this->_channel->joinEmail)>0){
			if(empty($this->_channel->joinEmail[0]->email->email_address)){
				$error='Xin lỗi, chủ trang web chưa cập nhật địa chỉ email để gửi yêu cầu!'; 
			}
		}else{
			$error='Xin lỗi, chủ trang web chưa cập nhật địa chỉ email để gửi yêu cầu!'; 
		}
		if(Session::has('buyNow')){
			if(empty($userName)){
				$error='Chưa có tên người liên hệ'; 
			}
			if(empty($userEmail)){
				$error='Chưa có email người mua'; 
			}
			if(empty($userPhone)){
				$error='Chưa có số điện thoại người mua'; 
			}
			if(empty($userAddress)){
				$error='Chưa có địa chỉ người mua'; 
			}
		}else{
			$error='Chưa có thông tin yêu cầu đặt mua! '; 
		}
		if(Auth::check()){
		$checkHistory=History::where('author','=',Auth::user()->id)
			->where('history_type','=','buyNowUser')
			->orderBy('created_at','asc')
			->first(); 
		}else{
			$checkHistory=History::where('author','=',Request::ip())
			->where('history_type','=','buyNowIp')
			->orderBy('created_at','asc')
			->first(); 
		}
		if(!empty($checkHistory->created_at)){
			if(Carbon::parse($checkHistory->created_at)->addMinutes(1) > Carbon::now()->format('Y-m-d H:i:s')){
				$error='Mỗi đơn hàng đặt cách nhau 1 phút. Lần tạo gần đây nhất của bạn cách đây '.WebService::time_request($checkHistory->created_at); 
			}
		}
		if(empty($error)){
			$getSession=Session::get('buyNow'); 
			$contentMessage=[ 
				'requestFrom'=>(!empty(Auth::user()->id)) ? 'user' : 'ip', 
				'itemId'=>$getSession['itemId'], 
				'itemName'=>$getSession['itemName'], 
				'itemPrice'=>$getSession['itemPrice'], 
				'link'=>$getSession['link'], 
				'userName' => $userName, 
				'userEmail' => $userEmail, 
				'userPhone' => $userPhone, 
				'userAddress' => $userAddress, 
				'buyQuanlity'=>$buyQuanlity, 
				'content'=>$content, 
			]; 
			Session::put('buyNow',$contentMessage); 
			$messageInsert=[
				'type'=>'buyNow', 
				'from'=>(!empty(Auth::user()->id)) ? Auth::user()->id : Request::ip(), 
				'to'=>$this->_channel->id, 
				'message_title'=>'Bạn nhận được đơn đặt hàng trên '.$this->_channel->channel_name, 
				'message_body'=>json_encode($contentMessage), 
				'message_status'=>'unread', 
				'message_send'=>'pending', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			]; 
			Messages::create($messageInsert); 
			if(Auth::check()){
				$listHistory=array(
					'history_type'=>'buyNowUser', 
					'parent_id'=>$this->_channel->id, 
					'author'=>Auth::user()->id,
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				); 
			}else{
				$listHistory=array(
					'history_type'=>'buyNowIp', 
					'parent_id'=>$this->_channel->id, 
					'author'=>Request::ip(),
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				);
			}
			History::insertGetId($listHistory); 
			return response()->json(['success'=>true, 
				'message'=>'Gửi yêu cầu thành công'
			]);
		}else{
			return response()->json(['success'=>false, 
				'message'=>$error, 
				'data'=>$userAddress
			]);
		}
	}
	public function cartRemove()
	{
		$cartId=Input::get('cartId'); 
		Cart::remove($cartId); 
		if(request()->ajax()){
			return response()->json(['success'=>true, 
				'message'=>'Đã xóa đơn hàng! ', 
			]);
		}
	}
	public function updateDomainInfo()
	{
		if(Auth::check()){
			$cartCollection = Cart::getContent(); 
			$return = array(
				'listCart'=>$cartCollection, 
				'totalPrice'=>Cart::getTotal()
			);
			return $this->_theme->scope('pay.updateDomainInfo', $return)->render();
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	public function updateDomainInfoProcess(){
		if(Session::has('Domains')){
			if(Auth::check()){
				$user=Auth::user(); 
				$customerName=Input::get('customerName'); 
				$customerSex=Input::get('customerSex'); 
				$customerBirthday=Input::get('customerBirthday'); 
				$customerPhone=Input::get('customerPhone'); 
				$customerEmail=Input::get('customerEmail'); 
				$customerAddress=Input::get('customerAddress'); 
				$customerCity=Input::get('customerCity'); 
				$customerCountry=Input::get('customerCountry'); 
				$error=''; 
				$getRegion=Regions::find($customerCountry); 
				$getSubregion=Subregions::find($customerCity); 
				if(empty($getSubregion->id)){
					$error='Bạn chưa chọn thành phố';
				}
				if(empty($getRegion->id)){
					$error='Bạn chưa chọn quốc gia';
				}
				$messages = array(
					'alpha_dash'=>'Địa chỉ kênh chỉ là dạng chữ không dấu và số',
					'required' => 'Vui lòng nhập thông tin (*).',
					'numeric' => 'Dữ liệu phải dạng số',
					'email' => 'Địa chỉ email không đúng', 
					'confirmed'=>'Nhập lại mật khẩu không chính xác'
				);
				$rules = array(
					'customerName' => 'required',
					'customerSex' => 'required|min:3',
					'customerBirthday' => 'required',
					'customerPhone'=>'required|numeric',
					'customerEmail'=>'required|email',
				);
				$validator = Validator::make(Input::all(), $rules, $messages);
				if ($validator->fails())
				{
					return response()->json(['success'=>false, 
						'message'=>$validator->errors()->first(), 
					]);
				}else{
					$getUser=User::find($user->id); 
					$getUser->gender=$customerSex; 
					$getUser->birthday=Carbon::createFromFormat('d/m/Y', $customerBirthday)->format('Y-m-d H:i:s');
					$getUser->save(); 
					if(!empty($getRegion->id)){
						Users_attribute::where('attribute_type','=','region')->where('parent_id','=',$user->id)->delete(); 
						Users_attribute::insertGetId(array(
							'parent_id'=>$user->id, 
							'attribute_type'=>'region', 
							'attribute_value'=>$getRegion->id, 
							'status'=>'active', 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
						)); 
					}
					if(!empty($getSubregion->id)){
						Users_attribute::where('attribute_type','=','subRegion')->where('parent_id','=',$user->id)->delete(); 
						Users_attribute::insertGetId(array(
							'parent_id'=>$user->id, 
							'attribute_type'=>'subRegion', 
							'attribute_value'=>$getSubregion->id, 
							'status'=>'active', 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
						)); 
					}
					$Domains=Session::get('Domains'); 
					$listDomain=json_decode($Domains,true); 
					if(count($listDomain)<=0){
						return response()->json(['success'=>false, 
							'message'=>'Chưa có tên miền nào được chọn! ', 
						]);
					}
					$message=[]; 
					$i=0;
					$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
					foreach($listDomain as $domain){
						$keyRandom=substr(str_shuffle(str_repeat($pool, 5)), 0, 5).strtotime(Carbon::now()->format('Y-m-d H:i:s')).$i;
						if(WebService::is_valid_url($domain['name'])){
							$domainName = $this->_rulesDomain->resolve($domain['name']);
							$serviceAttribute=Services_attribute::where('attribute_type','=',$domainName->getPublicSuffix())->first();
							if(!empty($serviceAttribute->id)){
								$checkExit='true';
								$colec=json_decode(Cart::getContent(),true);  
								if(count($colec)>0){
									foreach($colec as $checkColec){
										if($checkColec['name']==$domainName->getRegistrableDomain()){
											$checkExit='false';
											break; 
										}
									}
								}
								if($checkExit=='true'){
									$item = array(
										'id' => $keyRandom,
										'name' => $domainName->getRegistrableDomain(),
										'price' => $serviceAttribute->price_order+$serviceAttribute->price_re_order,
										'quantity' => 1,
										'attributes' => array(
											'type'=>'domainAddCart', 
											'service_name'=>$serviceAttribute->name, 
											'service_id'=>$serviceAttribute->id, 
											'author'=>$user->id, 
											'per'=>$serviceAttribute->per, 
											'customerName'=>$customerName, 
											'customerSex'=>$customerSex, 
											'customerBirthday'=>$customerBirthday, 
											'customerEmail'=>$customerEmail, 
											'customerPhone'=>$customerPhone, 
											'customerAddress'=>$customerAddress, 
											'customerCity'=>$getSubregion->subregions_name, 
											'customerCountry'=>$getRegion->iso
										)
									);
									Cart::add($item);
								}
							}else{
								$message['error']='Tên miền '.$domainName->getRegistrableDomain().' không được hỗ trợ!';
							}
						}
						$i++; 
					}
					return response()->json(['success'=>true, 
						'message'=>'Đã thêm tên miền vào giỏ hàng.', 
						'domains'=>$Domains, 
						'error'=>$message
					]);
				}
			}else{
				return response()->json(['success'=>false, 
					'message'=>'Bạn phải đăng nhập để tiếp tục ', 
				]);
			}
		}else{
			return response()->json(['success'=>false, 
				'message'=>'Chưa có tên miền nào được chọn! ', 
			]);
		}
	}
	public function addDomainSession()
	{
		$Domains=Input::get('Domains'); 
		Session::put('Domains',$Domains); 
		return response()->json(['success'=>true, 
			'message'=>'Đã thêm tên miền vào danh sách đặt hàng ', 
		]);
	}
	public function createCart()
	{
		if(Auth::check()){
			$cartType=Input::get('cartType'); 
			$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
			$keyRandom=substr(str_shuffle(str_repeat($pool, 5)), 0, 5).strtotime(Carbon::now()->format('Y-m-d H:i:s'));
			if($cartType=='channelAdd'){
				if($sessionChannel=Session::has('channelAdd')){
					$sessionChannel=Session::get('channelAdd'); 
					if(Session::has('packgeSelected')){
						$serviceAttribute=Services_attribute::find(Session::get('packgeSelected')); 
						if(!empty($serviceAttribute->id)){
							$item = array(
								'id' => $keyRandom,
								'name' => $sessionChannel['channelName'],
								'price' => $serviceAttribute->price_order+$serviceAttribute->price_re_order,
								'quantity' => 1,
								'attributes' => array(
									'type'=>$cartType, 
									'description'=>$sessionChannel['channelDescription'], 
									'fields'=>$sessionChannel['channelFields'], 
									'email'=>$sessionChannel['channelEmail'], 
									'phone'=>$sessionChannel['channelPhone'], 
									'address'=>$sessionChannel['channelAddress'], 
									'region'=>$sessionChannel['channelRegion'], 
									'subregion'=>$sessionChannel['channelSubRegion'], 
									'district'=>$sessionChannel['channelDistrict'], 
									'ward'=>$sessionChannel['channelWard'], 
									'channel_parent_id'=>$this->_channel->id, 
									'channel_service_id'=>Session::get('packgeSelected'), 
									'author'=>Auth::user()->id
								)
							);
							Cart::add($item);
							//Cart::remove('domainAdd');
							//Cart::add('domainAdd', 'Sample Item', 500000, 1, array()); 
							if(request()->ajax()){
								return response()->json(['success'=>true, 
									'message'=>'Đã thêm vào giỏ hàng của bạn. ', 
									'cartId'=>$keyRandom
								]);
							}
						}
					}
				}
			}else if($cartType=='channelUpgrade'){
				$packgeId=Input::get('packgeId'); 
				$serviceAttribute=Services_attribute::find($packgeId); 
				if(!empty($serviceAttribute->id)){
					$getServiceValue=json_decode($serviceAttribute->attribute_value); 
					$limitSize=$getServiceValue->limit_cloud; 
					$limitPosts=$getServiceValue->limit_post; 
					if($getServiceValue->limit_cloud<=$this->_limitSize){
						return response()->json(['success'=>false, 
							'message'=>'Gói nâng cấp phải có dung lượng lớn hơn hoặc khác gói đang sử dụng! ', 
						]);
					}else if($getServiceValue->limit_post<=$this->_limitPosts){
						return response()->json(['success'=>false, 
							'message'=>'Gói nâng cấp phải có số lượng bài lớn hơn hoặc khác gói đang sử dụng! ', 
						]);
					}else{
						$item = array(
							'id' => $keyRandom,
							'name' => $this->_channel->channel_name,
							'price' => $serviceAttribute->price_re_order,
							'quantity' => 1,
							'attributes' => array(
								'type'=>$cartType, 
								'channel_id'=>$this->_channel->id, 
								'channel_service_name'=>$serviceAttribute->name, 
								'channel_service_id'=>$serviceAttribute->id, 
								'per'=>$serviceAttribute->per,
								'author'=>Auth::user()->id
							)
						);
						Cart::add($item);
						if(request()->ajax()){
							return response()->json(['success'=>true, 
								'message'=>'Đã thêm vào đơn hàng của bạn. ', 
							]);
						}
					}
				}else{
					return response()->json(['success'=>false, 
						'message'=>'Chọn gói cần nâng cấp! ', 
					]);
				}
			}else if($cartType=='channelReOrder'){
				$channelId=Input::get('channelId'); 
				$checkChannel=Channel::find($channelId); 
				if(!empty($checkChannel->id)){
					$serviceAttribute=Services_attribute::find($checkChannel->service_attribute_id); 
					if(!empty($serviceAttribute->id)){
						$item = array(
							'id' => $keyRandom,
							'name' => $checkChannel->channel_name,
							'price' => $serviceAttribute->price_re_order,
							'quantity' => 1,
							'attributes' => array(
								'type'=>$cartType, 
								'channel_id'=>$checkChannel->id, 
								'channel_service_name'=>$serviceAttribute->name, 
								'channel_service_id'=>$serviceAttribute->id, 
								'per'=>$serviceAttribute->per,
								'author'=>Auth::user()->id
							)
						);
						Cart::add($item);
						if(request()->ajax()){
							return response()->json(['success'=>true, 
								'message'=>'Đã thêm vào đơn hàng của bạn ', 
							]);
						}
					}else{
						return response()->json(['success'=>false, 
							'message'=>'Không tìm thấy gói để gia hạn ', 
						]);
					}
				}else{
					return response()->json(['success'=>false, 
						'message'=>'Không tìm thấy gói gia hạn! ', 
					]);
				}
			}else if($cartType=='cloudReOrder'){
				$cloudId=Input::get('cloudId'); 
				$checkCloud=Cloud::find($cloudId); 
				if(!empty($checkCloud->id)){
					$serviceAttribute=Services_attribute::find($checkCloud->service_attribute_id); 
					if(!empty($serviceAttribute->id)){
						$item = array(
							'id' => $keyRandom,
							'name' => $checkCloud->name,
							'price' => $serviceAttribute->price_re_order,
							'quantity' => 1,
							'attributes' => array(
								'type'=>$cartType, 
								'service_id'=>$checkCloud->id, 
								'service_attribute_name'=>$serviceAttribute->name, 
								'service_attribute_id'=>$serviceAttribute->id, 
								'per'=>$serviceAttribute->per,
								'author'=>Auth::user()->id
							)
						);
						Cart::add($item);
						if(request()->ajax()){
							return response()->json(['success'=>true, 
								'message'=>'Đã thêm vào đơn hàng của bạn ', 
							]);
						}
					}else{
						return response()->json(['success'=>false, 
							'message'=>'Không tìm thấy gói để gia hạn ', 
						]);
					}
				}else{
					return response()->json(['success'=>false, 
						'message'=>'Không tìm thấy gói gia hạn! ', 
					]);
				}
			}else if($cartType=='hostingReOrder'){
				$hostingId=Input::get('hostingId'); 
				$checkHosting=Hosting::find($hostingId); 
				if(!empty($checkHosting->id)){
					$serviceAttribute=Services_attribute::find($checkHosting->service_attribute_id); 
					if(!empty($serviceAttribute->id)){
						$item = array(
							'id' => $keyRandom,
							'name' => $checkHosting->name,
							'price' => $serviceAttribute->price_re_order,
							'quantity' => 1,
							'attributes' => array(
								'type'=>$cartType, 
								'service_id'=>$checkHosting->id, 
								'service_attribute_name'=>$serviceAttribute->name, 
								'service_attribute_id'=>$serviceAttribute->id, 
								'per'=>$serviceAttribute->per,
								'author'=>Auth::user()->id
							)
						);
						Cart::add($item);
						if(request()->ajax()){
							return response()->json(['success'=>true, 
								'message'=>'Đã thêm vào đơn hàng của bạn ', 
							]);
						}
					}else{
						return response()->json(['success'=>false, 
							'message'=>'Không tìm thấy gói để gia hạn ', 
						]);
					}
				}else{
					return response()->json(['success'=>false, 
						'message'=>'Không tìm thấy gói gia hạn! ', 
					]);
				}
			}else if($cartType=='hostingAdd'){
				$hostingPackge=Input::get('hostingPackge'); 
				$serviceAttribute=Services_attribute::find($hostingPackge); 
				if(!empty($serviceAttribute->id)){ 
					$item = array(
						'id' => $keyRandom,
						'name' => $serviceAttribute->name,
						'price' => $serviceAttribute->price_re_order,
						'quantity' => 1,
						'attributes' => array(
							'type'=>$cartType, 
							'service_id'=>$serviceAttribute->id, 
							'service_attribute_name'=>$serviceAttribute->name, 
							'service_attribute_id'=>$serviceAttribute->id, 
							'per'=>$serviceAttribute->per,
							'author'=>Auth::user()->id
						)
					);
					Cart::add($item);
					if(request()->ajax()){
						return response()->json(['success'=>true, 
							'message'=>'Đã thêm vào đơn hàng của bạn ', 
						]);
					}
				}else{
					return response()->json(['success'=>false, 
						'message'=>'Không tìm thấy gói để gia hạn ', 
					]);
				}
			}else if($cartType=='cloudAdd'){
				$cloudPackge=Input::get('cloudPackge'); 
				$serviceAttribute=Services_attribute::find($cloudPackge); 
				if(!empty($serviceAttribute->id)){ 
					$item = array(
						'id' => $keyRandom,
						'name' => $serviceAttribute->name,
						'price' => $serviceAttribute->price_re_order,
						'quantity' => 1,
						'attributes' => array(
							'type'=>$cartType, 
							'service_id'=>$serviceAttribute->id, 
							'service_attribute_name'=>$serviceAttribute->name, 
							'service_attribute_id'=>$serviceAttribute->id, 
							'per'=>$serviceAttribute->per,
							'author'=>Auth::user()->id
						)
					);
					Cart::add($item);
					if(request()->ajax()){
						return response()->json(['success'=>true, 
							'message'=>'Đã thêm vào đơn hàng của bạn ', 
						]);
					}
				}else{
					return response()->json(['success'=>false, 
						'message'=>'Không tìm thấy gói để gia hạn ', 
					]);
				}
			}else if($cartType=='emailAdd'){
				$emailPackge=Input::get('emailPackge'); 
				$serviceAttribute=Services_attribute::find($emailPackge); 
				if(!empty($serviceAttribute->id)){ 
					$item = array(
						'id' => $keyRandom,
						'name' => $serviceAttribute->name,
						'price' => $serviceAttribute->price_re_order,
						'quantity' => 1,
						'attributes' => array(
							'type'=>$cartType, 
							'service_id'=>$serviceAttribute->id, 
							'service_attribute_name'=>$serviceAttribute->name, 
							'service_attribute_id'=>$serviceAttribute->id, 
							'per'=>$serviceAttribute->per,
							'author'=>Auth::user()->id
						)
					);
					Cart::add($item);
					if(request()->ajax()){
						return response()->json(['success'=>true, 
							'message'=>'Đã thêm vào đơn hàng của bạn ', 
						]);
					}
				}else{
					return response()->json(['success'=>false, 
						'message'=>'Không tìm thấy gói để gia hạn ', 
					]);
				}
			}else if($cartType=='domainAddCart'){
				$Domains=Input::get('Domains'); 
				$listDomain=json_decode($Domains,true); 
				if(count($listDomain)<=0){
					return response()->json(['success'=>false, 
						'message'=>'Chưa có tên miền nào được chọn! ', 
					]);
				}
				$message=[]; 
				$i=0;
				foreach($listDomain as $domain){
					$keyRandom=substr(str_shuffle(str_repeat($pool, 5)), 0, 5).strtotime(Carbon::now()->format('Y-m-d H:i:s')).$i;
					if(WebService::is_valid_url($domain['name'])){
						$domainName = $this->_parser->parseUrl($domain['name']); 
						$serviceAttribute=Services_attribute::where('attribute_type','=',$domainName->host->publicSuffix)->first(); 
						if(!empty($serviceAttribute->id)){
							$checkExit='true';
							$colec=json_decode(Cart::getContent(),true);  
							if(count($colec)>0){
								foreach($colec as $checkColec){
									if($checkColec['name']==$domainName->host->registerableDomain){
										$checkExit='false';
										break; 
									}
								}
							}
							if($checkExit=='true'){
								$item = array(
									'id' => $keyRandom,
									'name' => $domainName->host->registerableDomain,
									'price' => $serviceAttribute->price_order+$serviceAttribute->price_re_order,
									'quantity' => 1,
									'attributes' => array(
										'type'=>$cartType, 
										'service_name'=>$serviceAttribute->name, 
										'service_id'=>$serviceAttribute->id, 
										'author'=>Auth::user()->id, 
										'per'=>$serviceAttribute->per,
									)
								);
								Cart::add($item);
							}
						}else{
							$message['error']='Tên miền '.$domainName->host->registerableDomain.' không được hỗ trợ!';
						}
					}
					$i++; 
				}
				return response()->json(['success'=>true, 
					'message'=>'Đã thêm tên miền vào giỏ hàng.', 
					'domains'=>$Domains, 
					'error'=>$message
				]);
			}else if($cartType=='domainAdd'){
				$domainId=Input::get('domainId'); 
				$getDomain=Domain::find($domainId); 
				if(!empty($getDomain->id)){
					$serviceAttribute=Services_attribute::find($getDomain->service_attribute_id); 
					if(!empty($serviceAttribute->id)){
						$item = array(
							'id' => $keyRandom,
							'name' => $getDomain->domain,
							'price' => $serviceAttribute->price_order+$serviceAttribute->price_re_order,
							'quantity' => 1,
							'attributes' => array(
								'type'=>$cartType, 
								'domain_id'=>$getDomain->id, 
								'service_name'=>$serviceAttribute->name, 
								'service_id'=>$serviceAttribute->id, 
								'author'=>Auth::user()->id
							)
						);
						Cart::add($item);
						if(request()->ajax()){
							return response()->json(['success'=>true, 
								'message'=>'Đã thêm vào đơn hàng của bạn. ', 
							]);
						}
					}
				}
			}else if($cartType=='postOrder'){
				$postId=Input::get('postId'); 
				$getPost=Posts::find($postId); 
				if(!empty($getPost->id)){
					$item = array(
						'id' => $keyRandom,
						'name' => $getPost->posts_title,
						'price' => 20000,
						'quantity' => 1,
						'attributes' => array(
							'type'=>$cartType, 
							'post_id'=>$getPost->id, 
							'service_name'=>'Tin đăng', 
							'author'=>Auth::user()->id
						)
					);
					Cart::add($item);
					if(request()->ajax()){
						return response()->json(['success'=>true, 
							'message'=>'Đã thêm vào đơn hàng của bạn. ', 
						]);
					}
				}
			}
		}else{
			return response()->json(['success'=>false, 
				'message'=>'Bạn phải đăng nhập mới có thể tạo ', 
			]);
		}
	}
	public function getCart()
	{
		
	}
	/*--end new--*/
	public function cartOrderDelete()
	{
		$orderId=Input::get('orderId'); 
		Cart_order::where('id','=',$orderId)->where('status','!=','active')->delete(); 
		Cart_order_join::where('cart_order_parent_id','=',$orderId)->delete();
		Cart_order_attribute::where('parent_id','=',$orderId)->delete(); 
		return response()->json(['success'=>true, 
			'message'=>'Đã xóa đơn hàng. ', 
		]);
	}
	public function cartOrder()
	{
		$orderTable=Input::get('orderTable'); 
		$tableParentId=Input::get('tableParentId'); 
		$serviceId=Input::get('serviceId'); 
		$error=''; 
		$checkOrder=Cart_order_join::where('table','=',$orderTable)
		->where('table_parent_id','=',$tableParentId)
		->where('status','=','pending')
		->get(); 
		if(count($checkOrder)>0){
			$error='Đơn hàng đã được đặt nhưng chưa được xử lý'; 
		}
		if(empty($error)){
			$idCart=Cart_order::insertGetId(array(
				'name'=>$this->_channel->id, 
				'order_table'=>$orderTable, 
				'table_parent_id'=>$tableParentId, 
				'status'=>'pending', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
			if($idCart){
				Cart_order_attribute::insertGetId(array(
					'parent_id'=>$idCart, 
					'attribute_type'=>'quanlity', 
					'attribute_value'=>1, 
					'status'=>'pending', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				Cart_order_attribute::insertGetId(array(
					'parent_id'=>$idCart, 
					'attribute_type'=>'services_attribute_id', 
					'attribute_value'=>$serviceId, 
					'status'=>'pending', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				Cart_order_join::insertGetId(array(
					'table'=>$orderTable, 
					'table_parent_id'=>$tableParentId, 
					'cart_order_parent_id'=>$idCart, 
					'status'=>'pending', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				return response()->json(['success'=>true, 
					'message'=>'Đặt hàng thành công. ', 
				]);
			}
		}else{
			return response()->json(['success'=>false, 
				'message'=>$error, 
			]);
		}
	}
	public function cartShow()
    {
		if(Auth::check()){
			$getCart=Cart_order::where('table_parent_id','=',$this->_channel->id)->where('status','!=','active')->first(); 
			$view = array(
				'cartOrder'=>$getCart
			); 
			return $this->_theme->of('themes.admin.payment.cart', $view)->render(); 
		}else{
			$view = array();
			return $this->_theme->of('themes.admin.user.login', $view)->render(); 
		}
	}
	public function voucherCheck()
	{
		$voucherCode=Input::get('voucherCode'); 
		$error=''; 
		$checkVoucher=Voucher_attribute::where('attribute_value','=',$voucherCode)
		->where('attribute_type','=','code')
		->first(); 
		$cart=Cart_order::where('order_table','=','channel')
			->where('table_parent_id','=',$this->_channel->id)
			->where('status','!=','active')
			->first(); 
		if(empty($cart->id)){
			$idCart=Cart_order::insertGetId(array(
				'order_table'=>'channel',
				'table_parent_id'=>$this->_channel->id, 
				'status'=>'pending', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
			$cart=Cart_order::find($idCart); 
		}
		if(empty($checkVoucher->attribute_value)){
			$error='Không tìm thấy mã khuyến mãi'; 
		}else if($checkVoucher->status=='pending'){
			$error='Mã chưa được kích hoạt'; 
		}else if($checkVoucher->status=='delete'){
			$error='Mã đã bị xóa'; 
		}else if(!empty($cart->orderAttributeVoucher->item_id) && $cart->orderAttributeVoucher->item_id==$checkVoucher->id){
			$error='Mã giảm giá này đã được sử dụng'; 
		}else if(!empty($cart->orderAttributeVoucher->item_id)){
			$error='Đơn hàng đã có mã giảm giá'; 
		}
		if(empty($error)){ 
			$dataCartAttributeArray=array(
				'voucher_code'=>$checkVoucher->attribute_value, 
				'description'=>$checkVoucher->voucher->description, 
				'date_end'=>$checkVoucher->voucher->date_end, 
				'type'=>$checkVoucher->voucher->voucher_type, 
				'discount'=>$checkVoucher->voucher->discount, 
				'author_id'=>$checkVoucher->author->user->id, 
				'author'=>$checkVoucher->author->user->name, 
				'author_email'=>$checkVoucher->author->user->emailJoin->email->email_address, 
				'author_phone'=>(!empty($checkVoucher->author->user->phoneJoin->phone->phone_number)) ? $checkVoucher->author->user->phoneJoin->phone->phone_number : false, 
				
			); 
			$idCartAttribute=Cart_order_attribute::insertGetId(array(
				'parent_id'=>$cart->id, 
				'attribute_type'=>'voucher', 
				'item_id'=>$checkVoucher->id, 
				'attribute_value'=>json_encode($dataCartAttributeArray), 
				'status'=>'pending', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			));
			return response()->json(['success'=>true, 
				'id_attribute'=>$idCartAttribute, 
				'message'=>'Thông tin mã giảm giá', 
				'voucher_code'=>$checkVoucher->attribute_value, 
				'voucher_about'=>$checkVoucher->voucher->description, 
				'voucher_date_end'=>Site::Date($checkVoucher->voucher->date_end), 
				'type'=>$checkVoucher->voucher->voucher_type, 
				'discount'=>$checkVoucher->voucher->discount, 
				'author'=>$checkVoucher->author->user->name, 
				'author_email'=>$checkVoucher->author->user->emailJoin->email->email_address, 
				'author_phone'=>(!empty($checkVoucher->author->user->phoneJoin->phone->phone_number)) ? $checkVoucher->author->user->phoneJoin->phone->phone_number : false, 
			]);
		}else{
			return response()->json(['success'=>false, 
				'message'=>$error, 
			]);
		}
	}
	public function cartAttributeDelete()
	{
		$attributeId=Input::get('attributeId'); 
		Cart_order_attribute::where('id','=',$attributeId)->delete(); 
		return response()->json(['success'=>true, 
			'message'=>'Đã xóa đơn hàng', 
		]);
	}
	public function cartAdd()
    {
		$cartType=Input::get('cartType'); 
		$cartName=Input::get('cartName'); 
		$error=''; 
		$cart=Cart_order::where('order_table','=','channel')
			->where('table_parent_id','=',$this->_channel->id)
			->where('status','!=','active')
			->first(); 
		if(empty($cart->id)){
			$idCart=Cart_order::insertGetId(array(
				'order_table'=>'channel',
				'table_parent_id'=>$this->_channel->id, 
				'status'=>'pending', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
			$cart=Cart_order::find($idCart); 
		}
		if($cartType=='channel'){
			$service=Services::where('id','=',2)->first();
			$serviceAttribute=$service->attribute->find(3); 
			$orderName=$this->_channel->id; 
			$orderAttributeName=$this->_channel->channel_name; 
			$productId=$this->_channel->id; 
			$price_order=$serviceAttribute->price_order; 
			$price_re_order=$serviceAttribute->price_re_order; 
			$unit=$serviceAttribute->order_unit_month; 
			$per=$serviceAttribute->per; 
			$serviceAttributeId=$serviceAttribute->id; 
			if(!empty($serviceAttribute->id)){ 
				if(count($cart->attributeChannel)>0){
					foreach($cart->attributeChannel as $cartChannel){
						if($cartChannel->item_id==$this->_channel->id && $cartChannel->status!='active'){
							$error='Đơn hàng này đã có trong giỏ hàng của bạn! '; 
						}
					}
				}
			}else{
				$error='Không tìm thấy dịch vụ'; 
			}
		}else if($cartType=='domain'){
			$pslManager = new PublicSuffixListManager(); 
			$parser = new Parser($pslManager->getList()); 
			$domainName = $parser->parseUrl($cartName); 
			$service=Services::where('id','=',4)->first();
			$serviceAttribute=$service->attribute->where('attribute_type','=',$domainName->host->publicSuffix)->first(); 
			$orderAttributeName=$orderName=$domainName->host->registerableDomain; 
			$productId=Domain::where('domain','=',$domainName->host->registerableDomain)->first()->id; 
			$price_order=$serviceAttribute->price_order; 
			$price_re_order=$serviceAttribute->price_re_order; 
			$unit=$serviceAttribute->order_unit_month; 
			$per=$serviceAttribute->per; 
			$serviceAttributeId=$serviceAttribute->id; 
			if(!empty($serviceAttribute->id)){
				if(count($cart->attributeDomain)>0){
					foreach($cart->attributeDomain as $cartDomain){
						if($cartDomain->item_id==$domainName->host->registerableDomain && $cartDomain->status!='active'){
							$error='Tên miền này đã có trong giỏ hàng của bạn! '; 
						}
					}
				}
			}else{
				$error='Không tìm thấy dịch vụ'; 
			}
		}else if($cartType=='domainReOrder'){
			$pslManager = new PublicSuffixListManager(); 
			$parser = new Parser($pslManager->getList()); 
			$domainName = $parser->parseUrl($cartName); 
			$service=Services::where('id','=',4)->first();
			$serviceAttribute=$service->attribute->where('attribute_type','=',$domainName->host->publicSuffix)->first(); 
			$orderAttributeName=$orderName=$domainName->host->registerableDomain; 
			$productId=Domain::where('domain','=',$domainName->host->registerableDomain)->first()->id; 
			$price_order=0; 
			$price_re_order=$serviceAttribute->price_re_order; 
			$unit=$serviceAttribute->order_unit_month; 
			$per=$serviceAttribute->per; 
			$serviceAttributeId=$serviceAttribute->id; 
			if(!empty($serviceAttribute->id)){
				if(count($cart->attributeDomainReOrder)>0){
					foreach($cart->attributeDomainReOrder as $cartDomain){
						if($cartDomain->item_id==$domainName->host->registerableDomain && $cartDomain->status!='active' && $cartDomain->user_id==Auth::user()->id){
							$error='Tên miền này đã có trong giỏ hàng của bạn! '; 
						}
					}
				}
			}else{
				$error='Không tìm thấy dịch vụ'; 
			}
		}else if($cartType=='hostingReOrder'){
			$hosting=Hosting::find($cartName); 
			$orderAttributeName=$hosting->serviceAttribute->name.'-'.$hosting->name;
			$orderName=$hosting->id; 
			$productId=$hosting->id; 
			$price_order=0; 
			$price_re_order=$hosting->serviceAttribute->price_re_order; 
			$unit=$hosting->serviceAttribute->order_unit_month; 
			$per=$hosting->serviceAttribute->per; 
			$serviceAttributeId=$hosting->serviceAttribute->id; 
			if(!empty($hosting->serviceAttribute->id)){
				if(count($cart->attributeHostingReOrder)>0){
					foreach($cart->attributeHostingReOrder as $cartHosting){
						if($cartHosting->item_id==$hosting->id && $cartHosting->status!='active'){
							$error='Hosting này đã có trong giỏ hàng của bạn! '; 
						}
					}
				}
			}else{
				$error='Không tìm thấy dịch vụ'; 
			}
		}else if($cartType=='cloudReOrder'){
			$cloud=Cloud::find($cartName); 
			$orderAttributeName=$cloud->serviceAttribute->name.'-'.$cloud->name;
			$orderName=$cloud->id; 
			$productId=$cloud->id; 
			$price_order=0; 
			$price_re_order=$cloud->serviceAttribute->price_re_order; 
			$unit=$cloud->serviceAttribute->order_unit_month; 
			$per=$cloud->serviceAttribute->per; 
			$serviceAttributeId=$cloud->serviceAttribute->id; 
			if(!empty($cloud->serviceAttribute->id)){
				if(count($cart->attributeCloudReOrder)>0){
					foreach($cart->attributeCloudReOrder as $cartCloud){
						if($cartCloud->item_id==$cloud->id && $cartCloud->status!='active'){
							$error='Cloud này đã có trong giỏ hàng của bạn! '; 
						}
					}
				}
			}else{
				$error='Không tìm thấy dịch vụ'; 
			}
		}else if($cartType=='mail_serverReOrder'){
			$mail_server=Mail_server::find($cartName); 
			$orderAttributeName=$mail_server->serviceAttribute->name.'-'.$mail_server->name;
			$orderName=$mail_server->id; 
			$productId=$mail_server->id; 
			$price_order=0; 
			$price_re_order=$mail_server->serviceAttribute->price_re_order; 
			$unit=$mail_server->serviceAttribute->order_unit_month; 
			$per=$mail_server->serviceAttribute->per; 
			$serviceAttributeId=$mail_server->serviceAttribute->id; 
			if(!empty($mail_server->serviceAttribute->id)){
				if(count($cart->attributeMailServerReOrder)>0){
					foreach($cart->attributeMailServerReOrder as $cartMailserver){
						if($cartMailserver->item_id==$mail_server->id && $cartMailserver->status!='active'){
							$error='Mail Server này đã có trong giỏ hàng của bạn! '; 
						}
					}
				}
			}else{
				$error='Không tìm thấy dịch vụ'; 
			}
		}else{
			$error='Không tìm thấy loại đơn hàng'; 
		}
		if(empty($error)){
			$dataCartAttributeArray=array(
				'name'=>$orderAttributeName, 
				'price_order'=>$price_order, 
				'price_re_order'=>$price_re_order, 
				'quanlity'=>1, 
				'unit'=>$unit, 
				'per'=>$per, 
				'product_id'=>$productId,
				'service_attribute_id'=>$serviceAttributeId, 
				'author'=>Auth::user()->id
			); 
			Cart_order_attribute::insertGetId(array(
				'parent_id'=>$cart->id, 
				'attribute_type'=>$cartType, 
				'item_id'=>$orderName, 
				'user_id'=>Auth::user()->id,
				'attribute_value'=>json_encode($dataCartAttributeArray), 
				'status'=>'pending', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
			return response()->json(['success'=>true, 
				'message'=>'Đơn hàng đã được thêm vào giỏ hàng của bạn. ', 
			]);
		}else{
			return response()->json(['success'=>false, 
				'message'=>$error, 
			]);
		}
	}
}
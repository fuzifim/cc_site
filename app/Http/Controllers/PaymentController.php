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
use App\Model\Finance; 
use App\Permission;
use App\Role; 
use App\Model\Channel_role;
use Carbon\Carbon;
use App\Model\Region_district;
use App\Model\Region_ward;
use App\Model\Regions;
use App\Model\Subregions;
use App\Model\History; 
use App\Model\Channel; 
use App\Model\Channel_join;
use App\Model\Channel_attribute; 
use App\Model\Channel_join_region;
use App\Model\Channel_join_subregion;
use App\Model\Channel_join_district;
use App\Model\Channel_join_ward;
use App\Model\Channel_join_field; 
use App\Model\Channel_join_address;
use App\Model\Channel_join_email;
use App\Model\Channel_join_phone;
use App\Model\Company;
use App\Model\Company_join_channel;
use App\Model\Company_attribute;
use App\Model\Address;
use App\Model\Address_join_region;
use App\Model\Address_join_subregion;
use App\Model\Address_join_district;
use App\Model\Address_join_ward; 
use App\Model\Posts;
use App\Model\Domain;
use App\Model\Domain_join;
use App\Model\Domain_join_channel;
use App\Model\Domain_attribute; 
use App\Model\Email; 
use App\Model\Phone;
use App\Model\Hosting;
use App\Model\Hosting_join;
use App\Model\Cloud;
use App\Model\Cloud_join;
use App\Model\Mail_server;
use App\Model\Mail_server_join;
use App\Model\Order; 
use App\Model\Order_join_channel;
use App\Model\Order_join_user;
use App\Model\Pay_history; 
use App\Model\Pay_history_join_channel; 
use App\Model\Pay_history_join_user; 
use App\Model\Pay_order; 
use App\Model\Cart_order;
use App\Model\Cart_order_attribute;
use App\Model\Cart_order_join; 
use App\Model\Services; 
use App\Model\Services_attribute; 
use App\Model\Process_service; 
use App\Model\Voucher; 
use App\Model\Voucher_attribute; 
use App\Model\Voucher_join; 
use App\Model\Messages; 
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use File;
use Theme;
use WebService; 
use Route; 
use Redirect; 
use Response; 
use Input; 
use Validator; 
use Hash;
use Session; 
use Cart; 
use Site; 
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client; 
use App\Http\Controllers\ChannelController; 
use App\Http\Controllers\DomainController;
class PaymentController extends ConstructController
{
	public $_user; 
	public $_getOrder; 
	public $_paymentToken;  
	public $_paymentMethod;
	public $_paymentBankCode; 
	public $_paymentErrorCode; 
	public $_paymentTransictionId; 
	public $_bank_code; 
	public $_payment_method; 
	public $_order_code; 
	public $_total_amount; 
	public $_buyer_email; 
	public $_buyer_mobile; 
	public $_buyer_fullname; 
	public $_buyer_address; 
	public $_return_url; 
	public $_cancel_url; 
	public $_paymentType; 
	public function __construct(){
		parent::__construct(); 
	}
	/*-- Payment New--*/
	public function addMoney()
	{
		(int)$money=Input::get('money'); 
		$bank_code=Input::get('bankcode'); 
		$payment_method=Input::get('payment_method'); 
		$cancel_url=Input::get('cancel_url'); 
		$paymentType=Input::get('payment_type'); 
		if(!Auth::check()){
			$error='Bạn phải đăng nhập mới có thể nạp tiền'; 
		}
		if($money<20000){
			$error='Số tiền nạp phải lớn hơn 20.000đ'; 
		}else if(empty($bank_code)){
			$error='Bạn chưa chọn ngân hàng thanh toán'; 
		}else if(empty($payment_method)){
			$error='Chưa chọn phương thức thanh toán'; 
		}
		if(empty($error)){
			$this->_user=Auth::user(); 
			$this->_total_amount=$money; 
			$this->_bank_code=$bank_code; 
			$this->_payment_method=$payment_method; 
			$this->_cancel_url=$cancel_url; 
			$this->_buyer_address=html_entity_decode($this->_channel->joinAddress[0]->address->joinSubRegion->subregion->subregions_name.', '.$this->_channel->joinAddress[0]->address->joinRegion->region->country); 
			if($paymentType=='rePayment'){
				$this->_paymentType='rePayment'; 
				$this->_order_code=Input::get('orderId'); 
			}
			$addPayOrder=$this->addPayOrder(); 
			if($addPayOrder!=false){
				return response()->json(['success'=>true, 
					'message'=>'Đã thêm tiền vào đơn hàng thanh toán! ', 
					'checkout_url'=>$addPayOrder
				]);
			}else{
				return response()->json(['success'=>false, 
					'message'=>'Xảy ra lỗi trong quá trình thanh toán! ', 
				]);
			}
		}else{
			return response()->json(['success'=>false, 
				'message'=>$error
			]);
		}
	}
	public function addPayOrder(){
		if($this->_paymentType!='rePayment'){
			$this->_order_code=Pay_order::insertGetId(array(
				'money'=>$this->_total_amount, 
				'bank_code'=>$this->_bank_code, 
				'payment_method'=>$this->_payment_method, 
				'user_id'=>$this->_user->id, 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'status'=>'pending'
			)); 
		}
		if($this->_order_code){
			if(empty($this->_user->email)){
				$this->_buyer_email=config('app.email'); 
			}else{
				$this->_buyer_email=$this->_user->email; 
			}
			if(empty($this->_user->phone)){
				$this->_buyer_mobile=config('app.phone'); 
			}else{
				$this->_buyer_mobile=$this->_user->phone; 
			} 
			$this->_buyer_fullname=$this->_user->name; 
			$this->_return_url=route('pay.check.success',$this->_domainPrimary); 
			$processPayment=$this->processPayment(); 
			if($processPayment!=false){
				return $processPayment; 
			}else{
				return false; 
			}
		}
	}
	public function paymentCheckSuccess()
	{
		$nl_result = WebService::GetTransactionDetail(request('token')); 
		if($nl_result->error_code=='00'){
			$getOrder=Pay_order::find($nl_result->order_code); 
			$getHistory=Pay_history::where('token','=',$nl_result->token)->first(); 
			if(empty($getHistory->token)){
				if(!empty($getOrder->id) && $getOrder->status!='active'){ 
					$idFinance=Finance::insertGetId(array(
						'money'=>$getOrder->money, 
						'currency_code'=>'VND', 
						'pay_type'=>'add', 
						'user_id'=>$getOrder->user_id, 
						'pay_from'=>$getOrder->payment_method, 
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
					)); 
					if($idFinance){
						$getOrder->status='active'; 
						$getOrder->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
						$getOrder->save(); 
						$messageInsert=[
							'type'=>'paymentAdd', 
							'from'=>$getOrder->payment_method, 
							'to'=>$getOrder->user_id, 
							'message_title'=>'Nạp tiền vào tài khoản ', 
							'message_body'=>'Số tiền nạp '.$getOrder->money.'đ', 
							'message_status'=>'unread', 
							'message_send'=>'pending', 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
						]; 
						Messages::create($messageInsert); 
						$idPayHistory=Pay_history::insertGetId(array(
							'token'=>$nl_result->token, 
							'price'=>$getOrder->money,
							'payment_method'=>$nl_result->payment_method, 
							'bank_code'=>$nl_result->bank_code, 
							'response_code'=>$nl_result->error_code, 
							'transaction_id'=>$nl_result->transaction_id, 
							'order_id'=>$getOrder->id, 
							'type'=>'paymentAdd', 
							'message'=>'', 
							'status'=>'success', 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
						)); 
						if($idPayHistory){
							if(Auth::check()){
								Pay_history_join_user::insertGetId(array(
									'pay_history_id'=>$idPayHistory, 
									'user_id'=>$getOrder->user_id, 
								)); 
							}
						}
						return Redirect::route('pay.history',$this->_domainPrimary)->with('message_success', 'Nạp tiền vào tài khoản thành công!');
					}else{
						return Redirect::route('pay.history',$this->_domainPrimary)->with('message_danger', 'Không thể thực hiện được giao dịch!'); 
					}
					
				}else{
					return Redirect::route('pay.history',$this->_domainPrimary)->with('message_danger', 'Đơn hàng không tồn tại hoặc đã được thanh toán!'); 
				}
			}else{
				return Redirect::route('pay.history',$this->_domainPrimary)->with('message_danger', 'Giao dịch thanh toán này đã được xử lý!'); 
			}
		}else{
			return Redirect::route('channel.home',$this->_domainPrimary)->with('message_danger', 'Không tìm thấy thông tin thanh toán!'); 
		}
	}
	public function processPayment()
	{
		$payment_method=$this->_payment_method; 
		$bank_code=$this->_bank_code; 
		$buyer_email=$this->_buyer_email; 
		$buyer_mobile=$this->_buyer_mobile; 
		$buyer_fullname=$this->_buyer_fullname; 
		$buyer_address=$this->_buyer_address; 
		$order_code=$this->_order_code; 
		$total_amount=$this->_total_amount; 
		$return_url=$this->_return_url; 
		$cancel_url=$this->_cancel_url; 
		$payment_type =1;
		$discount_amount =0;
		$order_description='';
		$tax_amount=0;
		$fee_shipping=0; 
		$array_items=array();	
		if($payment_method !='' && $buyer_email !="" && $buyer_mobile !="" && $buyer_fullname !="" && filter_var( $buyer_email, FILTER_VALIDATE_EMAIL )  ){
			if($payment_method =="VISA"){
				$nl_result= Webservice::VisaCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount, $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, $buyer_address,$array_items,$bank_code);  
			}elseif($payment_method =="NL"){
				$nl_result= Webservice::NLCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount, $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, $buyer_address,$array_items);
													
			}elseif($payment_method =="ATM_ONLINE" && $bank_code !='' ){
				$nl_result= Webservice::BankCheckout($order_code,$total_amount,$bank_code,$payment_type,$order_description,$tax_amount, $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, $buyer_address,$array_items) ;
			}
			elseif($payment_method =="NH_OFFLINE"){
				$nl_result= Webservice::officeBankCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount,$return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
				}
			elseif($payment_method =="ATM_OFFLINE"){
				$nl_result= Webservice::BankOfflineCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
					
				}
			elseif($payment_method =="IB_ONLINE"){
				$nl_result= Webservice::IBCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
				}
			elseif ($payment_method == "CREDIT_CARD_PREPAID") {

				$nl_result = Webservice::PrepaidVisaCheckout($order_code, $total_amount, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items, $bank_code);
			}
			if($nl_result->error_code =='00'){
				return (string)$nl_result->checkout_url; 
			}else{
				return false; 
			}
		}else{
			return false; 
		}
	}
	public function payCartSend()
	{
		if(!Auth::check()){
			$error='Vui lòng đăng nhập để thanh toán';
		}
		if($this->_financeUserTotal<Cart::getTotal()){
			$error='Tài khoản của bạn không đủ để thanh toán, vui lòng nạp thêm! '; 
		}
		if(empty($error)){
			$order = new Order();
			$order->price=Cart::getTotal(); 
			$order->cart=Cart::getContent()->toJson(); 
			$order->status='pending'; 
			$order->created_at=Carbon::now()->format('Y-m-d H:i:s'); 
			$order->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
			$order->save(); 
			$orderJoinChannel=new Order_join_channel(); 
			$orderJoinChannel->order_id=$order->id; 
			$orderJoinChannel->channel_id=$this->_channel->id; 
			$orderJoinChannel->save(); 
			$orderJoinUser=new Order_join_user(); 
			$orderJoinUser->order_id=$order->id; 
			$orderJoinUser->user_id=Auth::user()->id; 
			$orderJoinUser->save();  
			$this->_getOrder=Order::find($order->id); 
			$result = $this->successPay();
			if($result==true){
				Finance::insertGetId(array(
					'money'=>Cart::getTotal(), 
					'currency_code'=>'VND', 
					'pay_type'=>'remove', 
					'user_id'=>Auth::user()->id, 
					'pay_from'=>'account', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				Cart::clear();
				return response()->json(['success'=>true, 
					'message'=>'Thanh toán thành công', 
				]);
			}else{
				return response()->json(['success'=>false, 
					'message'=>'Xảy ra lỗi trong quá trình thanh toán! ', 
				]);
			}
		}else{
			return response()->json(['success'=>false, 
				'message'=>$error, 
			]);
		}
	}
	public function successPay()
	{
		$processCart=json_decode($this->_getOrder->cart); 
		foreach($processCart as $key=>$item){
			if($item->attributes->type=='channelAdd'){
				$channelControl=new ChannelController(); 
				$channelControl->_addChannelDomain=$item->attributes->domain; 
				$channelControl->_addChannelDomainLtd=$item->attributes->domainLtd; 
				$channelControl->_addChannelName=$item->name;  
				$channelControl->_addChannelDescription=$item->attributes->description;  
				$channelControl->_addChannelEmail=$item->attributes->email;  
				$channelControl->_addChannelPhone=$item->attributes->phone; 
				$channelControl->_addChannelField=$item->attributes->fields; 
				$channelControl->_addChannelAddress=$item->attributes->address;  
				$channelControl->_addChannelRegion=$item->attributes->region; 
				$channelControl->_addChannelSubRegion=$item->attributes->subregion;  
				$channelControl->_addChannelDistrict=$item->attributes->district;  
				$channelControl->_addChannelWard=$item->attributes->ward; 
				$channelControl->_addChannelServiceId=$item->attributes->channel_service_id; 
				$channelControl->_addChannelParentId=$item->attributes->channel_parent_id;  
				$channelControl->_addChannelAuthor=$item->attributes->author; 
				$channelControl->_addChannelDateEnd=Carbon::parse(Carbon::now()->format('Y-m-d H:i:s'))->addMonth($item->quantity)->format('Y-m-d H:i:s'); 
				$channelControl->_addChannelStatus='active'; 
				$result=$channelControl->addChannel(); 
			}else if($item->attributes->type=='channelUpgrade'){
				$getChannel=Channel::find($item->attributes->channel_id); 
				if(!empty($getChannel->id)){
					$getChannel->service_attribute_id=$item->attributes->channel_service_id; 
					$getChannel->channel_updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
					$getChannel->channel_date_end=Carbon::parse($getChannel->channel_date_end)->addMonth($item->quantity)->format('Y-m-d H:i:s'); 
					$getChannel->save(); 
					$contentMessage=[
						'channel'=>$getChannel, 
					];
					$messageInsert=[
						'type'=>'channelUpgrade', 
						'from'=>$item->attributes->channel_id, 
						'to'=>$item->attributes->author, 
						'message_title'=>'Nâng cấp gói '.$getChannel->channel_name, 
						'message_body'=>json_encode($contentMessage), 
						'message_status'=>'unread', 
						'message_send'=>'pending', 
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
					]; 
					Messages::create($messageInsert); 
				}
			}else if($item->attributes->type=='channelReOrder'){
				$getChannel=Channel::find($item->attributes->channel_id); 
				if(!empty($getChannel->id)){
					$getChannel->channel_updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
					$getChannel->channel_date_end=Carbon::parse($getChannel->channel_date_end)->addMonth($item->quantity)->format('Y-m-d H:i:s'); 
					$getChannel->channel_status='active'; 
					$getChannel->notify=0; 
					$getChannel->save(); 
					$contentMessage=[
						'channel'=>$getChannel, 
					];
					$messageInsert=[
						'type'=>'channelReOrder', 
						'from'=>$item->attributes->channel_id, 
						'to'=>$item->attributes->author, 
						'message_title'=>'Gia hạn thành công '.$getChannel->channel_name, 
						'message_body'=>json_encode($contentMessage), 
						'message_status'=>'unread', 
						'message_send'=>'pending', 
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
					]; 
					Messages::create($messageInsert); 
				}
			}else if($item->attributes->type=='postOrder'){
				$getPost=Posts::find($item->attributes->post_id); 
				if(!empty($getPost->id)){
					$getPost->posts_updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
					$getPost->posts_status='active'; 
					$getPost->save(); 
					$contentMessage=[
						'post'=>$getPost, 
					];
					$messageInsert=[
						'type'=>'postOrder', 
						'from'=>$item->attributes->post_id, 
						'to'=>$item->attributes->author, 
						'message_title'=>'Thanh toán bài đăng: '.$getPost->posts_title.' thành công', 
						'message_body'=>json_encode($contentMessage), 
						'message_status'=>'unread', 
						'message_send'=>'pending', 
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
					]; 
					Messages::create($messageInsert); 
				}
				
			}else if($item->attributes->type=='cloudReOrder'){
				Process_service::insertGetId(array(
					'order_id'=>$this->_getOrder->id, 
					'type'=>'cloudReOrder', 
					'content'=>json_encode($item), 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'status'=>'pending', 
					'replay'=>0
				)); 
				
			}else if($item->attributes->type=='hostingReOrder'){
				Process_service::insertGetId(array(
					'order_id'=>$this->_getOrder->id, 
					'type'=>'hostingReOrder', 
					'content'=>json_encode($item), 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'status'=>'pending', 
					'replay'=>0
				));
				
			}else if($item->attributes->type=='hostingAdd'){
				Process_service::insertGetId(array(
					'order_id'=>$this->_getOrder->id, 
					'type'=>'hostingAdd', 
					'content'=>json_encode($item), 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'status'=>'pending', 
					'replay'=>0
				));
				
			}else if($item->attributes->type=='cloudAdd'){
				Process_service::insertGetId(array(
					'order_id'=>$this->_getOrder->id, 
					'type'=>'cloudAdd', 
					'content'=>json_encode($item), 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'status'=>'pending', 
					'replay'=>0
				));
				
			}else if($item->attributes->type=='emailAdd'){
				Process_service::insertGetId(array(
					'order_id'=>$this->_getOrder->id, 
					'type'=>'emailAdd', 
					'content'=>json_encode($item), 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'status'=>'pending', 
					'replay'=>0
				));
				
			}else if($item->attributes->type=='domainAddCart'){
				Process_service::insertGetId(array(
					'order_id'=>$this->_getOrder->id, 
					'type'=>'domainAddCart', 
					'content'=>json_encode($item), 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'status'=>'pending', 
					'replay'=>0
				));
				
			}else if($item->attributes->type=='domainAdd'){
				Process_service::insertGetId(array(
					'order_id'=>$this->_getOrder->id, 
					'type'=>'domainAdd', 
					'content'=>json_encode($item), 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'status'=>'pending', 
					'replay'=>0
				));
				
			}
		}
		$this->_getOrder->status='active'; 
		$this->_getOrder->save(); 
		return true; 
	}
	public function payAdd(){ 
		if(Auth::check()){
			$return=array(); 
			return $this->_theme->scope('pay.add', $return)->render();
		}else{
			return 'Bạn không có quyền truy cập trang này! '; 
		}
	}
	public function payHistory(){ 
		if(Auth::check()){
			$return=array(); 
			return $this->_theme->scope('pay.history', $return)->render();
		}else{
			return 'Bạn không có quyền truy cập trang này! '; 
		}
	}
	public function payOrderPendingDelete()
	{
		if(Auth::check()){
			$orderId=Input::get('orderId'); 
			$getOrder=Pay_order::find($orderId); 
			if(!empty($getOrder->id)|| $getOrder->status!='active'){
				$getOrder->delete(); 
				return response()->json(['success'=>true, 
					'message'=>'Xóa giao dịch thành công!  '
				]);
			}else{
				return response()->json(['success'=>false, 
					'message'=>'Không thể xóa giao dịch này! '
				]);
			}
		}
	}
	/*-- End Payment New--*/
	public function payCart()
	{
		if(Auth::check()){
			$cartCollection = Cart::getContent(); 
			$return = array(
				'listCart'=>$cartCollection, 
				'totalPrice'=>Cart::getTotal()
			);
			return $this->_theme->scope('pay.show', $return)->render();
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	public function updateQualityCart()
	{
		$cartId=Input::get('cartId'); 
		$quanlityChange=Input::get('quanlityChange'); 
		Cart::update($cartId, array(
			'quantity' => array(
				  'relative' => false,
				  'value' => $quanlityChange
			  ), // so if the current product has a quantity of 4, another 2 will be added so this will result to 6
		)); 
		return response()->json(['success'=>true, 
			'message'=>'Đã cập nhật số lượng đơn hàng '
		]);
	}
	public function payCreate(){
		
	}
	public function orderDelete()
	{
		if(Auth::check()){
			$orderId=Input::get('orderId'); 
			$getOrder=Order::find($orderId); 
			if(!empty($getOrder->id)|| $getOrder->status!='active'){
				$getOrder->delete(); 
				return response()->json(['success'=>true, 
					'message'=>'Đã xóa đơn hàng của bạn!  '
				]);
			}else{
				return response()->json(['success'=>false, 
					'message'=>'Không thể xóa đơn hàng! '
				]);
			}
		}
	}
	
	public function payCheck()
	{
		$nl_result = WebService::GetTransactionDetail(request('token')); 
		if($nl_result->error_code=='00'){
			$getOrder=Order::where('id','=',$nl_result->order_code)->first(); 
			$getHistory=Pay_history::where('token','=',request('token'))->first(); 
			$this->_getOrder= $getOrder; 
			if(empty($getHistory->token)){
				if(!empty($getOrder->id) && $getOrder->status!='active'){ 
					$getOrder->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
					$getOrder->save(); 
					$orderId=$getOrder->id; 
					$status='success'; 
					$message='Thanh toán thành công đơn hàng.'; 
					
				}else{
					$orderId=$getOrder->id; 
					$status='failed'; 
					$message='Thanh toán không thành công. Không tìm thấy đơn hàng để thanh toán hoặc đơn hàng đã được thanh toán.  '; 
				}
				if($status=='success'){
					$this->_paymentToken=$nl_result->token; 
					$this->_paymentMethod=$nl_result->payment_method; 
					$this->_paymentBankCode=$nl_result->bank_code; 
					$this->_paymentErrorCode=$nl_result->error_code;  
					$this->_paymentTransictionId=$nl_result->transaction_id; 
					$result = $this->successPay(); 
					if($result==true){
						return Redirect::route('pay.history',$this->_domain->domain)->with('message_success', 'Đơn hàng của bạn đã được thanh toán thành công!');
						Cart::clear();
					}else{
						return Redirect::route('pay.history',$this->_domain->domain)->with('message_danger', 'Không thể thực hiện được giao dịch!'); 
					}
				} 
			}else{
				return Redirect::route('pay.history',$this->_domain->domain)->with('message_danger', 'Giao dịch thanh toán này đã được xử lý!'); 
			}
		}else{
			return Redirect::route('channel.home',$this->_domain->domain)->with('message_danger', 'Không tìm thấy thông tin thanh toán!'); 
		}
	}
	public function paymentNL()
    {
		$orderId=Input::get('orderId'); 
		$bank_code=Input::get('bankcode'); 
		$payment_method=Input::get('payment_method'); 
		$voucherCode=Input::get('voucherCode'); 
		$quanlitySelect=Input::get('quanlity'); 
		$getOrder=Cart_order::where('id','=',$orderId)->first();
		if(empty($getOrder->id)){
			$error='Không tìm thấy đơn hàng.'; 
		}else if(empty($bank_code)){
			$error='Chưa chọn ngân hàng thanh toán'; 
		}else if(empty($payment_method)){
			$error='Chưa chọn phương thức thanh toán'; 
		}else if(empty(Auth::user()->emailJoin->email->email_address)){
			$error='Tài khoản của bạn chưa có địa chỉ Email. Vui lòng cập nhật để thanh toán'; 
		}else if(empty(Auth::user()->phoneJoin->phone->phone_number)){
			$error='Tài khoản của bạn chưa có số điện thoại. Vui lòng cập nhật để thanh toán'; 
		}
		if(empty($error)){
			$quanlityJsonDecode=json_decode($quanlitySelect); 
			foreach($quanlityJsonDecode as  $key=>$quanlity){
				$getAttribute=$getOrder->attribute->find($key); 
				$attribute=json_decode($getAttribute->attribute_value); 
				$attributeArray=array(
					'name'=>$attribute->name, 
					'price_order'=>$attribute->price_order, 
					'price_re_order'=>$attribute->price_re_order, 
					'quanlity'=>$quanlity, 
					'unit'=>$attribute->unit, 
					'per'=>$attribute->per, 
					'service_attribute_id'=>$attribute->service_attribute_id, 
					'author'=>Auth::user()->id
				); 
				$getAttribute->attribute_value=json_encode($attributeArray); 
				$getAttribute->save(); 
			}
			$getVoucherCart=$getOrder->attribute->where('attribute_type','=','voucher')->first(); 
			$totalPrice=0; 
			foreach($getOrder->attribute as $cart){ 
				if($cart->attribute_type!='voucher'){
					$attributeCart=json_decode($cart->attribute_value); 
					$price=($attributeCart->price_order+$attributeCart->price_re_order)*$attributeCart->quanlity; 
					$getVoucherCart=$getOrder->attribute->where('attribute_type','=','voucher')->first(); 
					foreach($getOrder->attribute as $voucher){
						if($voucher->attribute_type=='voucher'){
							$attributeVoucher=json_decode($voucher->attribute_value); 
							if($attributeVoucher->type==$cart->attribute_type){
								$price=$price*($attributeVoucher->discount/100); 
							}
						}
					}
					$totalPrice+=$price; 
				}
			}
			$order_code=$getOrder->id; 
			$total_amount=$totalPrice; 
			$buyer_email=Auth::user()->emailJoin->email->email_address; 
			$buyer_mobile=Auth::user()->phoneJoin->phone->phone_number; 
			$buyer_fullname=Auth::user()->name; 
			$buyer_address=html_entity_decode($this->_channel->channelJoinSubRegion->subregion->subregions_name.', '.$this->_channel->channelJoinRegion->region->country); 
			$return_url=route('channel.payment.check',$this->_domain->domain); 
			$cancel_url=Input::get('cancel_url'); 
			$payment_type =1;
			$discount_amount =0;
			$order_description='';
			$tax_amount=0;
			$fee_shipping=0; 
			$array_items=array();	
			if($payment_method !='' && $buyer_email !="" && $buyer_mobile !="" && $buyer_fullname !="" && filter_var( $buyer_email, FILTER_VALIDATE_EMAIL )  ){
				if($payment_method =="VISA"){
					$nl_result= Webservice::VisaCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount, $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, $buyer_address,$array_items,$bank_code);  
				}elseif($payment_method =="NL"){
					$nl_result= Webservice::NLCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount, $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, $buyer_address,$array_items);
														
				}elseif($payment_method =="ATM_ONLINE" && $bank_code !='' ){
					$nl_result= Webservice::BankCheckout($order_code,$total_amount,$bank_code,$payment_type,$order_description,$tax_amount, $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, $buyer_address,$array_items) ;
				}
				elseif($payment_method =="NH_OFFLINE"){
					$nl_result= Webservice::officeBankCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount,$return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
					}
				elseif($payment_method =="ATM_OFFLINE"){
					$nl_result= Webservice::BankOfflineCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
						
					}
				elseif($payment_method =="IB_ONLINE"){
					$nl_result= Webservice::IBCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
					}
				elseif ($payment_method == "CREDIT_CARD_PREPAID") {

					$nl_result = Webservice::PrepaidVisaCheckout($order_code, $total_amount, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items, $bank_code);
				}
				if($nl_result->error_code =='00'){
					return response()->json(['success'=>true, 
						'message'=>'Tiến hành thanh toán', 
						'checkout_url'=>(string)$nl_result->checkout_url
					]);
				}else{
					return response()->json(['success'=>false, 
						'message'=>$nl_result->error_message
					]);
				}
			}else{
				return response()->json(['success'=>false, 
					'message'=>'bạn chưa nhập đủ thông tin thanh toán! '
				]);
			}
			
		}else{
			return response()->json(['success'=>false, 
				'message'=>$error
			]);
		}
	}
	public function checkPayment()
    {
		$nl_result = WebService::GetTransactionDetail(request('token')); 
		if($nl_result->error_code=='00'){
			$getOrder=Cart_order::where('id','=',$nl_result->order_code)->first(); 
			if(!empty($getOrder->id)){
				if($getOrder->status!='active'){
					$today=Carbon::now()->format('Y-m-d H:i:s'); 
					foreach($getOrder->attribute as $cart){
						$attribute=json_decode($cart->attribute_value); 
						if($cart->attribute_type=='channel'){
							$channel=Channel::find($attribute->product_id); 
							if($channel->channel_date_billing_active<$today){
								$channel->channel_date_billing_active=$today; 
							}
							if($channel->channel_date_end<$today){
								$channel->channel_date_end=Carbon::parse($today)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
							}else{
								$channel->channel_date_end=Carbon::parse($channel->channel_date_end)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
							}
							$channel->save(); 
							$serviceAttribute=Services_attribute::find($attribute->service_attribute_id); 
							$channel->channelService->updated_at=$today; 
							$channel->channelService->table_parent_id=$serviceAttribute->service->id; 
							$channel->service_attribute_id=$attribute->service_attribute_id; 
							$channel->notify=0; 
							$channel->channelService->save(); 
							foreach($getOrder->attribute as $voucher){
								if($voucher->attribute_type=='voucher'){
									$attributeVoucher=json_decode($voucher->attribute_value); 
									if($attributeVoucher->type==$cart->attribute_type){
										Channel_attribute::where('channel_parent_id','=',$channel->id)->where('channel_attribute_type','=','reseller')->delete(); 
										Channel_attribute::insertGetId(array(
											'channel_parent_id'=>$channel->id, 
											'channel_attribute_type'=>'reseller', 
											'channel_attribute_value'=>$attributeVoucher->author_id, 
											'channel_attribute_status'=>'active', 
											'channel_attribute_created_at'=>$today, 
											'channel_attribute_updated_at'=>$today
										)); 
									}
								}
							}
						}elseif($cart->attribute_type=='domain'){
							$getDomain=Domain::find($attribute->product_id); 
							if(!empty($getDomain->id) && $getDomain->domain_location=='register' && $getDomain->status!='active'){
								$idDomain=$getDomain->id; 
								$getDomain->status='active'; 
								if($getDomain->date_begin<$today){
									$getDomain->date_begin=$today; 
								}
								if($getDomain->date_end<$today){
									$getDomain->date_end=Carbon::parse($today)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
								}else{
									$getDomain->date_end=Carbon::parse($getDomain->date_end)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
								}
								$getDomain->notify=0; 
								$getDomain->save(); 
								if(!empty($getDomain->domainJoinChannel->table_parent_id)){
									$getDomain->domainJoinChannel->table_parent_id=$getOrder->table_parent_id; 
									$getDomain->domainJoinChannel->save(); 
								}else{
									Domain_join::insertGetId(array(
										'table'=>'channel', 
										'table_parent_id'=>$getOrder->table_parent_id, 
										'domain_parent_id'=>$getDomain->id, 
										'status'=>'active', 
										'created_at'=>$today, 
										'updated_at'=>$today
									)); 
								}
								$serviceAttribute=Services_attribute::find($attribute->service_attribute_id); 
								if(!empty($getDomain->joinService->table_parent_id)){
									$getDomain->joinService->table_parent_id=$serviceAttribute->service->id; 
									$getDomain->joinService->save(); 
								}else{
									Domain_join::insertGetId(array(
										'table'=>'services', 
										'table_parent_id'=>$serviceAttribute->service->id, 
										'domain_parent_id'=>$getDomain->id, 
										'status'=>'active', 
										'created_at'=>$today, 
										'updated_at'=>$today
									)); 
								}
								if(!empty($getDomain->attributeAuthor->attribute_value)){
									$getDomain->attributeAuthor->attribute_value=$attribute->author; 
									$getDomain->attributeAuthor->save(); 
								}else{
									Domain_attribute::insertGetId(array(
										'parent_id'=>$getDomain->id, 
										'attribute_type'=>'author', 
										'attribute_value'=>$attribute->author, 
										'attribute_status'=>'active', 
										'attribute_created_at'=>$today, 
										'attribute_updated_at'=>$today
									)); 
								}
							}else{
								$newDomain=new DomainController(); 
								$newDomain->_domainRegister=$cart->item_id; 
								$newDomain->_domainRegisterPrimary='none'; 
								$newDomain->_domainRegisterLocation='register'; 
								$newDomain->_domainRegisterServiceId=2; 
								$newDomain->_domainRegisterStatus='active'; 
								$newDomain->_domainRegisterCreatedAt= $today; 
								$newDomain->_domainRegisterUpdateAt=$today; 
								$newDomain->_domainRegisterDateBegin=$today; 
								$newDomain->_domainRegisterDateEnd=Carbon::parse($today)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s'); 
								$idDomain=$newDomain->addDomain(); 
								if($idDomain){
									Domain_join::insertGetId(array(
										'table'=>'channel', 
										'table_parent_id'=>$getOrder->table_parent_id, 
										'domain_parent_id'=>$idDomain, 
										'status'=>'active', 
										'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
									)); 
									$serviceAttribute=Services_attribute::find($attribute->service_attribute_id); 
									Domain_join::insertGetId(array(
										'table'=>'services', 
										'table_parent_id'=>$attribute->service_attribute_id, 
										'domain_parent_id'=>$idDomain, 
										'status'=>'active', 
										'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
									)); 
									Domain_attribute::insertGetId(array(
										'parent_id'=>$idDomain, 
										'attribute_type'=>'author', 
										'attribute_value'=>$attribute->author, 
										'attribute_status'=>'active', 
										'attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
									)); 
								}
							}
							$contentMessage=[
								'domain'=>Domain::find($idDomain), 
								'channel'=>$this->_channel
							];
							if(!empty($this->_channel->channelParent->id)){
								$resellerId=$this->_channel->channelParent->id; 
							}else{
								$resellerId=1; 
							}
							$messageInsertToReseller=[
								'type'=>'domainAddMsgToReseller', 
								'from'=>Auth::user()->id, 
								'to'=>$resellerId, 
								'message_title'=>'Đăng ký tên miền '.$cart->item_id.' đã thanh toán trên '.$this->_channel->channel_name, 
								'message_body'=>json_encode($contentMessage), 
								'message_status'=>'unread', 
								'message_send'=>'pending', 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
							]; 
							Messages::create($messageInsertToReseller); 
						}elseif($cart->attribute_type=='domainReOrder'){
							$getDomain=Domain::find($attribute->product_id); 
							
							if($getDomain->date_begin<$today){
								$getDomain->date_begin=$today; 
							}
							if($getDomain->date_end<$today){
								$getDomain->date_end=Carbon::parse($today)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
							}else{
								$getDomain->date_end=Carbon::parse($getDomain->date_end)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
							}
							$getDomain->notify=0; 
							$getDomain->save(); 
							$contentMessage=[
								'domain'=>$getDomain, 
								'channel'=>$this->_channel
							];
							if(!empty($this->_channel->channelParent->id)){
								$resellerId=$this->_channel->channelParent->id; 
							}else{
								$resellerId=1; 
							}
							$messageInsertToReseller=[
								'type'=>'domainReOrderMsgToReseller', 
								'from'=>Auth::user()->id, 
								'to'=>$resellerId, 
								'message_title'=>'Gia hạn tên miền '.$cart->item_id.' đã thanh toán trên '.$this->_channel->channel_name, 
								'message_body'=>json_encode($contentMessage), 
								'message_status'=>'unread', 
								'message_send'=>'pending', 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
							]; 
							Messages::create($messageInsertToReseller); 
						}elseif($cart->attribute_type=='hostingReOrder'){
							$getHosting=Hosting::find($attribute->product_id); 
							if($getHosting->date_begin<$today){
								$getHosting->date_begin=$today; 
							}
							if($getHosting->date_end<$today){
								$getHosting->date_end=Carbon::parse($today)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
							}else{
								$getHosting->date_end=Carbon::parse($getHosting->date_end)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
							}
							$getHosting->notify=0; 
							$getHosting->save(); 
							$contentMessage=[
								'hosting'=>$getHosting, 
								'channel'=>$this->_channel
							];
							if(!empty($this->_channel->channelParent->id)){
								$resellerId=$this->_channel->channelParent->id; 
							}else{
								$resellerId=1; 
							}
							$messageInsertToReseller=[
								'type'=>'hostingReOrderMsgToReseller', 
								'from'=>Auth::user()->id, 
								'to'=>$resellerId, 
								'message_title'=>'Gia hạn hosting '.$getHosting->name.' đã thanh toán trên '.$this->_channel->channel_name, 
								'message_body'=>json_encode($contentMessage), 
								'message_status'=>'unread', 
								'message_send'=>'pending', 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
							]; 
							Messages::create($messageInsertToReseller); 
						}elseif($cart->attribute_type=='cloudReOrder'){
							$getCloud=Cloud::find($attribute->product_id); 
							if($getCloud->date_begin<$today){
								$getCloud->date_begin=$today; 
							}
							if($getCloud->date_end<$today){
								$getCloud->date_end=Carbon::parse($today)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
							}else{
								$getCloud->date_end=Carbon::parse($getCloud->date_end)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
							}
							$getCloud->notify=0; 
							$getCloud->save(); 
							$contentMessage=[
								'cloud'=>$getCloud, 
								'channel'=>$this->_channel
							];
							if(!empty($this->_channel->channelParent->id)){
								$resellerId=$this->_channel->channelParent->id; 
							}else{
								$resellerId=1; 
							}
							$messageInsertToReseller=[
								'type'=>'cloudReOrderMsgToReseller', 
								'from'=>Auth::user()->id, 
								'to'=>$resellerId, 
								'message_title'=>'Gia hạn cloud '.$getCloud->name.' đã thanh toán trên '.$this->_channel->channel_name, 
								'message_body'=>json_encode($contentMessage), 
								'message_status'=>'unread', 
								'message_send'=>'pending', 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
							]; 
							Messages::create($messageInsertToReseller); 
						}elseif($cart->attribute_type=='mail_serverReOrder'){
							$getMailServer=Mail_server::find($attribute->product_id); 
							if($getMailServer->date_begin<$today){
								$getMailServer->date_begin=$today; 
							}
							if($getMailServer->date_end<$today){
								$getMailServer->date_end=Carbon::parse($today)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
							}else{
								$getMailServer->date_end=Carbon::parse($getMailServer->date_end)->addMonth($attribute->unit*$attribute->quanlity)->format('Y-m-d H:i:s');  
							}
							$getMailServer->notify=0; 
							$getMailServer->save(); 
							$contentMessage=[
								'mail_server'=>$getMailServer, 
								'channel'=>$this->_channel
							];
							if(!empty($this->_channel->channelParent->id)){
								$resellerId=$this->_channel->channelParent->id; 
							}else{
								$resellerId=1; 
							}
							$messageInsertToReseller=[
								'type'=>'cloudReOrderMsgToReseller', 
								'from'=>Auth::user()->id, 
								'to'=>$resellerId, 
								'message_title'=>'Gia hạn Mail Server '.$getMailServer->name.' đã thanh toán trên '.$this->_channel->channel_name, 
								'message_body'=>json_encode($contentMessage), 
								'message_status'=>'unread', 
								'message_send'=>'pending', 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
							]; 
							Messages::create($messageInsertToReseller); 
						}
					}
					$getOrder->status='active'; 
					$getOrder->save(); 
					return Redirect::route('channel.admin.dashboard',$this->_domain->domain)->with('message_success', 'Thanh toán thành công'); 
				}else{
					return Redirect::route('channel.admin.dashboard',$this->_domain->domain)->with('message_danger', 'Đơn hàng này đã được thanh toán'); 
				}
			}else{
				return Redirect::route('channel.admin.dashboard',$this->_domain->domain)->with('message_danger', 'Không tìm thấy đơn hàng thanh toán!'); 
			}
		}else{
				return Redirect::route('channel.admin.dashboard',$this->_domain->domain)->with('message_danger', 'Không tìm thấy thông tin thanh toán!'); 
		}
	}
}

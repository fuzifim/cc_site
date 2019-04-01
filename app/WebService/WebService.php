<?php

namespace WebService;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User; 
use App\Permission;
use App\Role;
use App\Model\Main; 
use App\Model\Company; 
use App\Model\Company_join; 
use App\Model\Company_attribute; 
use App\Model\Channel; 
use App\Model\Channel_join;
use App\Model\Channel_attribute; 
use App\Model\Domain;
use App\Model\Domain_join;
use App\Model\Domain_attribute;
use App\Model\Fields;
use App\Model\Posts;
use App\Model\Posts_join;
use App\Model\Posts_attribute; 
use App\Model\Category;
use App\Model\Category_join;
use App\Model\Slug;
use App\Model\Media; 
use App\Model\Media_join;
use App\Model\Users; 
use App\Model\Users_join; 
use App\Model\Email;
use App\Model\Email_join;
use App\Model\Phone;
use App\Model\Phone_join; 
use App\Model\Like; 
use App\Model\Messages; 
use App\Model\Comments; 
use App\Model\Comments_join; 
use App\Model\Comments_attribute;
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use Imagick;
use File;
use Youtube; 
use Agent;
use Auth;
use Input;
use DB;
use Hash;
use Closure;
use Validator;
use Session;
use Redirect;
use Mail;
use Site;
use Response;
use Carbon\Carbon;
use Theme;
use URL;
use Route;
use Config;
class WebService {
	protected $url_api;  
	protected $merchant_id;
	protected $merchant_password;
	protected $receiver_email;
	protected $cur_code;
	public function __construct()
	{
		$this->cur_code='vnd';
		$this->version ='3.1';
		$this->url_api =config('app.NGANLUONG_URL_API');
		$this->merchant_id = config('app.NGANLUONG_MERCHANT_ID');
		$this->merchant_password = config('app.NGANLUONG_MERCHANT_PASS');
		$this->receiver_email = config('app.NGANLUONG_RECEIVER');		
    }
		
	public function GetTransactionDetail($token){	
		$params = array(
			'merchant_id'       => $this->merchant_id ,
			'merchant_password' => MD5($this->merchant_password),
			'version'           => $this->version,
			'function'          => 'GetTransactionDetail',
			'token'             => $token
		);						
		
		$post_field = '';
		foreach ($params as $key => $value){
			if ($post_field != '') $post_field .= '&';
			$post_field .= $key."=".$value;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url_api);
		curl_setopt($ch, CURLOPT_ENCODING , 'UTF-8');
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		$error = curl_error($ch);
		
		if ($result != '' && $status==200){
			$nl_result  = simplexml_load_string($result);						
			return $nl_result;
		}
		
		return false;
	  
	}	
	/*

	Hàm lấy link thanh toán bằng thẻ visa
	===============================
	Tham số truyền vào bắt buộc phải có
				order_code
				total_amount
				payment_method

				buyer_fullname
				buyer_email
				buyer_mobile
	===============================			
		$array_items mảng danh sách các item name theo quy tắc 
		item_name1
		item_quantity1
		item_amount1
		item_url1
		.....
		payment_type Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
	 */			
public function VisaCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount,
							$fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, 
							$buyer_address,$array_items,$bank_code) 
		{
		 $params = array(
				'cur_code'				=>	$this->cur_code,
				'function'				=> 'SetExpressCheckout',
				'version'				=> $this->version,
				'merchant_id'			=> $this->merchant_id, //Mã merchant khai báo tại NganLuong.vn
				'receiver_email'		=> $this->receiver_email,
				'merchant_password'		=> MD5($this->merchant_password), //MD5(Mật khẩu kết nối giữa merchant và NganLuong.vn)						
				'order_code'			=> $order_code, //Mã hóa đơn do website bán hàng sinh ra
				'total_amount'			=> $total_amount, //Tổng số tiền của hóa đơn
				'payment_method'		=> 'VISA', //Phương thức thanh toán, nhận một trong các giá trị 'VISA','ATM_ONLINE', 'ATM_OFFLINE' hoặc 'NH_OFFLINE'												
				'bank_code'				=> $bank_code, //Phương thức thanh toán, nhận một trong các giá trị 'VISA','ATM_ONLINE', 'ATM_OFFLINE' hoặc 'NH_OFFLINE'												
				'payment_type'			=> $payment_type, //Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
				'order_description'		=> $order_description, //Mô tả đơn hàng
				'tax_amount'			=> $tax_amount, //Tổng số tiền thuế
				'fee_shipping'			=> $fee_shipping, //Phí vận chuyển
				'discount_amount'		=> $discount_amount, //Số tiền giảm giá
				'return_url'			=> $return_url, //Địa chỉ website nhận thông báo giao dịch thành công
				'cancel_url'			=> $cancel_url, //Địa chỉ website nhận "Hủy giao dịch"
				'buyer_fullname'		=> $buyer_fullname, //Tên người mua hàng
				'buyer_email'			=> $buyer_email, //Địa chỉ Email người mua
				'buyer_mobile'			=> $buyer_mobile, //Điện thoại người mua
				'buyer_address'			=> $buyer_address, //Địa chỉ người mua hàng
				'total_item'			=> count($array_items)
			);
			$post_field = '';
			foreach ($params as $key => $value){
				if ($post_field != '') $post_field .= '&';
				$post_field .= $key."=".$value;
			}
			if(count($array_items)>0){
			 foreach($array_items as $array_item){
				foreach ($array_item as $key => $value){
					if ($post_field != '') $post_field .= '&';
					$post_field .= $key."=".$value;
				}
			}
			}
		//die($post_field);
			
		$nl_result=$this->CheckoutCall($post_field);
		return $nl_result;
	}
public function PrepaidVisaCheckout($order_code, $total_amount, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items, $bank_code) {
	$params = array(
		'cur_code' => $this->cur_code,
		'function' => Config::$_FUNCTION,
		'version' => Config::$_VERSION,
		'merchant_id' => $this->merchant_id, //Mã merchant khai báo tại NganLuong.vn
		'receiver_email' => $this->receiver_email,
		'merchant_password' => MD5($this->merchant_password), //MD5(Mật khẩu kết nối giữa merchant và NganLuong.vn)						
		'order_code' => $order_code, //Mã hóa đơn do website bán hàng sinh ra
		'total_amount' => $total_amount, //Tổng số tiền của hóa đơn
		'payment_method' => 'CREDIT_CARD_PREPAID', //Phương thức thanh toán, nhận một trong các giá trị 'VISA','ATM_ONLINE', 'ATM_OFFLINE' hoặc 'NH_OFFLINE'												
		'bank_code' => $bank_code, //Phương thức thanh toán, nhận một trong các giá trị 'VISA','ATM_ONLINE', 'ATM_OFFLINE' hoặc 'NH_OFFLINE'												
		'payment_type' => $payment_type, //Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
		'order_description' => $order_description, //Mô tả đơn hàng
		'tax_amount' => $tax_amount, //Tổng số tiền thuế
		'fee_shipping' => $fee_shipping, //Phí vận chuyển
		'discount_amount' => $discount_amount, //Số tiền giảm giá
		'return_url' => $return_url, //Địa chỉ website nhận thông báo giao dịch thành công
		'cancel_url' => $cancel_url, //Địa chỉ website nhận "Hủy giao dịch"
		'buyer_fullname' => $buyer_fullname, //Tên người mua hàng
		'buyer_email' => $buyer_email, //Địa chỉ Email người mua
		'buyer_mobile' => $buyer_mobile, //Điện thoại người mua
		'buyer_address' => $buyer_address, //Địa chỉ người mua hàng
		'total_item' => count($array_items)
	);
	//var_dump($params); exit;
	$post_field = '';
	foreach ($params as $key => $value) {
		if ($post_field != '')
			$post_field .= '&';
		$post_field .= $key . "=" . $value;
	}
	if (count($array_items) > 0) {
		foreach ($array_items as $array_item) {
			foreach ($array_item as $key => $value) {
				if ($post_field != '')
					$post_field .= '&';
				$post_field .= $key . "=" . $value;
			}
		}
	}
	//die($post_field);

	$nl_result = $this->CheckoutCall($post_field);
	return $nl_result;
}
		/*
		Hàm lấy link thanh toán qua ngân hàng
		===============================
		Tham số truyền vào bắt buộc phải có
					order_code
					total_amount			
					bank_code // Theo bảng mã ngân hàng
					
					buyer_fullname
					buyer_email
					buyer_mobile
		===============================	
			
			$array_items mảng danh sách các item name theo quy tắc 
			item_name1
			item_quantity1
			item_amount1
			item_url1
			.....			
			payment_type Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn

		*/			  
public function BankCheckout($order_code,$total_amount,$bank_code,$payment_type,$order_description,$tax_amount,
							$fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, 
							$buyer_address,$array_items) 
   {
		 $params = array(
				'cur_code'				=>	$this->cur_code,
				'function'				=> 'SetExpressCheckout',
				'version'				=> $this->version,
				'merchant_id'			=> $this->merchant_id, //Mã merchant khai báo tại NganLuong.vn
				'receiver_email'		=> $this->receiver_email,
				'merchant_password'		=> MD5($this->merchant_password), //MD5(Mật khẩu kết nối giữa merchant và NganLuong.vn)						
				'order_code'			=> $order_code, //Mã hóa đơn do website bán hàng sinh ra
				'total_amount'			=> $total_amount, //Tổng số tiền của hóa đơn						
				'payment_method'		=> 'ATM_ONLINE', //Phương thức thanh toán, nhận một trong các giá trị 'ATM_ONLINE', 'ATM_OFFLINE' hoặc 'NH_OFFLINE'
				'bank_code'				=> $bank_code, //Mã Ngân hàng
				'payment_type'			=> $payment_type, //Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
				'order_description'		=> $order_description, //Mô tả đơn hàng
				'tax_amount'			=> $tax_amount, //Tổng số tiền thuế
				'fee_shipping'			=> $fee_shipping, //Phí vận chuyển
				'discount_amount'		=> $discount_amount, //Số tiền giảm giá
				'return_url'			=> $return_url, //Địa chỉ website nhận thông báo giao dịch thành công
				'cancel_url'			=> $cancel_url, //Địa chỉ website nhận "Hủy giao dịch"
				'buyer_fullname'		=> $buyer_fullname, //Tên người mua hàng
				'buyer_email'			=> $buyer_email, //Địa chỉ Email người mua
				'buyer_mobile'			=> $buyer_mobile, //Điện thoại người mua
				'buyer_address'			=> $buyer_address, //Địa chỉ người mua hàng
				'total_item'			=> count($array_items)
			);
			
			$post_field = '';
			foreach ($params as $key => $value){
				if ($post_field != '') $post_field .= '&';
				$post_field .= $key."=".$value;
			}
			if(count($array_items)>0){
			 foreach($array_items as $array_item){
				foreach ($array_item as $key => $value){
					if ($post_field != '') $post_field .= '&';
					$post_field .= $key."=".$value;
				}
			}
			}
		//$post_field="function=SetExpressCheckout&version=3.1&merchant_id=24338&receiver_email=payment@hellochao.com&merchant_password=5b39df2b8f3275d1c8d1ea982b51b775&order_code=macode_oerder123&total_amount=2000&payment_method=ATM_ONLINE&bank_code=ICB&payment_type=&order_description=&tax_amount=0&fee_shipping=0&discount_amount=0&return_url=http://localhost/testcode/nganluong.vn/checkoutv3/payment_success.php&cancel_url=http://nganluong.vn&buyer_fullname=Test&buyer_email=saritvn@gmail.com&buyer_mobile=0909224002&buyer_address=&total_item=1&item_name1=Product name&item_quantity1=1&item_amount1=2000&item_url1=http://nganluong.vn/"	;
		//echo $post_field;
		//die;
		$nl_result=$this->CheckoutCall($post_field);
		
		return $nl_result;
	}
			
public function BankOfflineCheckout($order_code,$total_amount,$bank_code,$payment_type,$order_description,$tax_amount,
							$fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, 
							$buyer_address,$array_items) 
   {
		 $params = array(
				'cur_code'				=>	$this->cur_code,
				'function'				=> 'SetExpressCheckout',
				'version'				=> $this->version,
				'merchant_id'			=> $this->merchant_id, //Mã merchant khai báo tại NganLuong.vn
				'receiver_email'		=> $this->receiver_email,
				'merchant_password'		=> MD5($this->merchant_password), //MD5(Mật khẩu kết nối giữa merchant và NganLuong.vn)						
				'order_code'			=> $order_code, //Mã hóa đơn do website bán hàng sinh ra
				'total_amount'			=> $total_amount, //Tổng số tiền của hóa đơn						
				'payment_method'		=> 'ATM_OFFLINE', //Phương thức thanh toán, nhận một trong các giá trị 'ATM_ONLINE', 'ATM_OFFLINE' hoặc 'NH_OFFLINE'
				'bank_code'				=> $bank_code, //Mã Ngân hàng
				'payment_type'			=> $payment_type, //Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
				'order_description'		=> $order_description, //Mô tả đơn hàng
				'tax_amount'			=> $tax_amount, //Tổng số tiền thuế
				'fee_shipping'			=> $fee_shipping, //Phí vận chuyển
				'discount_amount'		=> $discount_amount, //Số tiền giảm giá
				'return_url'			=> $return_url, //Địa chỉ website nhận thông báo giao dịch thành công
				'cancel_url'			=> $cancel_url, //Địa chỉ website nhận "Hủy giao dịch"
				'buyer_fullname'		=> $buyer_fullname, //Tên người mua hàng
				'buyer_email'			=> $buyer_email, //Địa chỉ Email người mua
				'buyer_mobile'			=> $buyer_mobile, //Điện thoại người mua
				'buyer_address'			=> $buyer_address, //Địa chỉ người mua hàng
				'total_item'			=> count($array_items)
			);
			
			$post_field = '';
			foreach ($params as $key => $value){
				if ($post_field != '') $post_field .= '&';
				$post_field .= $key."=".$value;
			}
			if(count($array_items)>0){
			 foreach($array_items as $array_item){
				foreach ($array_item as $key => $value){
					if ($post_field != '') $post_field .= '&';
					$post_field .= $key."=".$value;
				}
			}
			}
		//$post_field="function=SetExpressCheckout&version=3.1&merchant_id=24338&receiver_email=payment@hellochao.com&merchant_password=5b39df2b8f3275d1c8d1ea982b51b775&order_code=macode_oerder123&total_amount=2000&payment_method=ATM_ONLINE&bank_code=ICB&payment_type=&order_description=&tax_amount=0&fee_shipping=0&discount_amount=0&return_url=http://localhost/testcode/nganluong.vn/checkoutv3/payment_success.php&cancel_url=http://nganluong.vn&buyer_fullname=Test&buyer_email=saritvn@gmail.com&buyer_mobile=0909224002&buyer_address=&total_item=1&item_name1=Product name&item_quantity1=1&item_amount1=2000&item_url1=http://nganluong.vn/"	;
		//echo $post_field;
		//die;
		$nl_result=$this->CheckoutCall($post_field);
		
		return $nl_result;
	}
			
			
public function officeBankCheckout($order_code,$total_amount,$bank_code,$payment_type,$order_description,$tax_amount,
						$fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, 
						$buyer_address,$array_items) 
	{
		 $params = array(
				'cur_code'				=> $this->cur_code,
				'function'				=> 'SetExpressCheckout',
				'version'				=> $this->version,
				'merchant_id'			=> $this->merchant_id, //Mã merchant khai báo tại NganLuong.vn
				'receiver_email'		=> $this->receiver_email,
				'merchant_password'		=> MD5($this->merchant_password), //MD5(Mật khẩu kết nối giữa merchant và NganLuong.vn)						
				'order_code'			=> $order_code, //Mã hóa đơn do website bán hàng sinh ra
				'total_amount'			=> $total_amount, //Tổng số tiền của hóa đơn						
				'payment_method'		=> 'NH_OFFLINE', //Phương thức thanh toán, nhận một trong các giá trị 'ATM_ONLINE', 'ATM_OFFLINE' hoặc 'NH_OFFLINE'
				'bank_code'				=> $bank_code, //Mã Ngân hàng
				'payment_type'			=> $payment_type, //Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
				'order_description'		=> $order_description, //Mô tả đơn hàng
				'tax_amount'			=> $tax_amount, //Tổng số tiền thuế
				'fee_shipping'			=> $fee_shipping, //Phí vận chuyển
				'discount_amount'		=> $discount_amount, //Số tiền giảm giá
				'return_url'			=> $return_url, //Địa chỉ website nhận thông báo giao dịch thành công
				'cancel_url'			=> $cancel_url, //Địa chỉ website nhận "Hủy giao dịch"
				'buyer_fullname'		=> $buyer_fullname, //Tên người mua hàng
				'buyer_email'			=> $buyer_email, //Địa chỉ Email người mua
				'buyer_mobile'			=> $buyer_mobile, //Điện thoại người mua
				'buyer_address'			=> $buyer_address, //Địa chỉ người mua hàng
				'total_item'			=> count($array_items)
			);
			
			$post_field = '';
			foreach ($params as $key => $value){
				if ($post_field != '') $post_field .= '&';
				$post_field .= $key."=".$value;
			}
			if(count($array_items)>0){
			 foreach($array_items as $array_item){
				foreach ($array_item as $key => $value){
					if ($post_field != '') $post_field .= '&';
					$post_field .= $key."=".$value;
				}
			}
			}
		//$post_field="function=SetExpressCheckout&version=3.1&merchant_id=24338&receiver_email=payment@hellochao.com&merchant_password=5b39df2b8f3275d1c8d1ea982b51b775&order_code=macode_oerder123&total_amount=2000&payment_method=ATM_ONLINE&bank_code=ICB&payment_type=&order_description=&tax_amount=0&fee_shipping=0&discount_amount=0&return_url=http://localhost/testcode/nganluong.vn/checkoutv3/payment_success.php&cancel_url=http://nganluong.vn&buyer_fullname=Test&buyer_email=saritvn@gmail.com&buyer_mobile=0909224002&buyer_address=&total_item=1&item_name1=Product name&item_quantity1=1&item_amount1=2000&item_url1=http://nganluong.vn/"	;
		//echo $post_field;
		//die;
		$nl_result=$this->CheckoutCall($post_field);
		
		return $nl_result;
	}

/*

Hàm lấy link thanh toán tại văn phòng ngân lượng

===============================
Tham số truyền vào bắt buộc phải có
			order_code
			total_amount			
			bank_code // HN hoặc HCM
			
			buyer_fullname
			buyer_email
			buyer_mobile
===============================	
	
	$array_items mảng danh sách các item name theo quy tắc 
	item_name1
	item_quantity1
	item_amount1
	item_url1
	.....			
	payment_type Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn

*/			  
public function TTVPCheckout($order_code,$total_amount,$bank_code,$payment_type,$order_description,$tax_amount,
							$fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, 
							$buyer_address,$array_items) 
{
	 $params = array(
			'cur_code'			=>	$this->cur_code,
			'function'				=> 'SetExpressCheckout',
			'version'				=> $this->version,
			'merchant_id'			=> $this->merchant_id, //Mã merchant khai báo tại NganLuong.vn
			'receiver_email'		=> $this->receiver_email,
			'merchant_password'		=> MD5($this->merchant_password), //MD5(Mật khẩu kết nối giữa merchant và NganLuong.vn)						
			'order_code'			=> $order_code, //Mã hóa đơn do website bán hàng sinh ra
			'total_amount'			=> $total_amount, //Tổng số tiền của hóa đơn						
			'payment_method'		=> 'ATM_ONLINE', //Phương thức thanh toán, nhận một trong các giá trị 'ATM_ONLINE', 'ATM_OFFLINE' hoặc 'NH_OFFLINE'
			'bank_code'				=> $bank_code, //Mã Ngân hàng
			'payment_type'			=> $payment_type, //Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
			'order_description'		=> $order_description, //Mô tả đơn hàng
			'tax_amount'			=> $tax_amount, //Tổng số tiền thuế
			'fee_shipping'			=> $fee_shipping, //Phí vận chuyển
			'discount_amount'		=> $discount_amount, //Số tiền giảm giá
			'return_url'			=> $return_url, //Địa chỉ website nhận thông báo giao dịch thành công
			'cancel_url'			=> $cancel_url, //Địa chỉ website nhận "Hủy giao dịch"
			'buyer_fullname'		=> $buyer_fullname, //Tên người mua hàng
			'buyer_email'			=> $buyer_email, //Địa chỉ Email người mua
			'buyer_mobile'			=> $buyer_mobile, //Điện thoại người mua
			'buyer_address'			=> $buyer_address, //Địa chỉ người mua hàng
			'total_item'			=> count($array_items)
		);
		
		$post_field = '';
		foreach ($params as $key => $value){
			if ($post_field != '') $post_field .= '&';
			$post_field .= $key."=".$value;
		}
		if(count($array_items)>0){
		 foreach($array_items as $array_item){
			foreach ($array_item as $key => $value){
				if ($post_field != '') $post_field .= '&';
				$post_field .= $key."=".$value;
			}
		}
		}
		
	$nl_result=$this->CheckoutCall($post_field);
	return $nl_result;
}
			
			/*

			Hàm lấy link thanh toán dùng số dư ví ngân lượng
			===============================
			Tham số truyền vào bắt buộc phải có
						order_code
						total_amount
						payment_method

						buyer_fullname
						buyer_email
						buyer_mobile
			===============================			
				$array_items mảng danh sách các item name theo quy tắc 
				item_name1
				item_quantity1
				item_amount1
				item_url1
				.....

				payment_type Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
			 */			
	public function NLCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount,
							$fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile, 
							$buyer_address,$array_items) 
	{
		 $params = array(
				'cur_code'				=> $this->cur_code,
				'function'				=> 'SetExpressCheckout',
				'version'				=> $this->version,
				'merchant_id'			=> $this->merchant_id, //Mã merchant khai báo tại NganLuong.vn
				'receiver_email'		=> $this->receiver_email,
				'merchant_password'		=> MD5($this->merchant_password), //MD5(Mật khẩu kết nối giữa merchant và NganLuong.vn)						
				'order_code'			=> $order_code, //Mã hóa đơn do website bán hàng sinh ra
				'total_amount'			=> $total_amount, //Tổng số tiền của hóa đơn						
				'payment_method'		=> 'NL', //Phương thức thanh toán
				'payment_type'			=> $payment_type, //Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
				'order_description'		=> $order_description, //Mô tả đơn hàng
				'tax_amount'			=> $tax_amount, //Tổng số tiền thuế
				'fee_shipping'			=> $fee_shipping, //Phí vận chuyển
				'discount_amount'		=> $discount_amount, //Số tiền giảm giá
				'return_url'			=> $return_url, //Địa chỉ website nhận thông báo giao dịch thành công
				'cancel_url'			=> $cancel_url, //Địa chỉ website nhận "Hủy giao dịch"
				'buyer_fullname'		=> $buyer_fullname, //Tên người mua hàng
				'buyer_email'			=> $buyer_email, //Địa chỉ Email người mua
				'buyer_mobile'			=> $buyer_mobile, //Điện thoại người mua
				'buyer_address'			=> $buyer_address, //Địa chỉ người mua hàng
				'total_item'			=> count($array_items) //Tổng số sản phẩm trong đơn hàng
			);
			$post_field = '';
			foreach ($params as $key => $value){
				if ($post_field != '') $post_field .= '&';
				$post_field .= $key."=".$value;
			}
			if(count($array_items)>0){
				foreach($array_items as $array_item){
					foreach ($array_item as $key => $value){
						if ($post_field != '') $post_field .= '&';
						$post_field .= $key."=".$value;
					}
				}
			}
		$nl_result=$this->CheckoutCall($post_field);
		return $nl_result;
	}
				
	public function IBCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items) {
        $params = array(
            'cur_code' => $this->cur_code,
            'function' => 'SetExpressCheckout',
            'version' => $this->version,
            'merchant_id' => $this->merchant_id, //Mã merchant khai báo tại NganLuong.vn
            'receiver_email' => $this->receiver_email,
            'merchant_password' => MD5($this->merchant_password), //MD5(Mật khẩu kết nối giữa merchant và NganLuong.vn)						
            'order_code' => $order_code, //Mã hóa đơn do website bán hàng sinh ra
            'total_amount' => $total_amount, //Tổng số tiền của hóa đơn						
            'payment_method' => 'IB_ONLINE', //Phương thức thanh toán
			'bank_code' => $bank_code,
            'payment_type' => $payment_type, //Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
            'order_description' => $order_description, //Mô tả đơn hàng
            'tax_amount' => $tax_amount, //Tổng số tiền thuế
            'fee_shipping' => $fee_shipping, //Phí vận chuyển
            'discount_amount' => $discount_amount, //Số tiền giảm giá
            'return_url' => $return_url, //Địa chỉ website nhận thông báo giao dịch thành công
            'cancel_url' => $cancel_url, //Địa chỉ website nhận "Hủy giao dịch"
            'buyer_fullname' => $buyer_fullname, //Tên người mua hàng
            'buyer_email' => $buyer_email, //Địa chỉ Email người mua
            'buyer_mobile' => $buyer_mobile, //Điện thoại người mua
            'buyer_address' => $buyer_address, //Địa chỉ người mua hàng
            'total_item' => count($array_items) //Tổng số sản phẩm trong đơn hàng
        );
        $post_field = '';
        foreach ($params as $key => $value) {
            if ($post_field != '')
                $post_field .= '&';
            $post_field .= $key . "=" . $value;
        }
        if (count($array_items) > 0) {
            foreach ($array_items as $array_item) {
                foreach ($array_item as $key => $value) {
                    if ($post_field != '')
                        $post_field .= '&';
                    $post_field .= $key . "=" . $value;
                }
            }
        }
        $nl_result = $this->CheckoutCall($post_field);
        return $nl_result;
    }			
			
	public function CheckoutCall($post_field){		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url_api);
		curl_setopt($ch, CURLOPT_ENCODING , 'UTF-8');
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		$error = curl_error($ch);
		if ($result != '' && $status==200){						
			$xml_result = str_replace('&','&amp;',(string)$result);
			$nl_result  = simplexml_load_string($xml_result);					
			$nl_result->error_message = $this->GetErrorMessage($nl_result->error_code);										
		}
		else $nl_result->error_message = $error;
		return $nl_result;
	}
			
	public function GetErrorMessage($error_code) {
		$arrCode = array(
		'00' => 'Thành công',
		'99' => 'Lỗi chưa xác minh',
		'06' => 'Mã merchant không tồn tại hoặc bị khóa',
		'02' => 'Địa chỉ IP truy cập bị từ chối',
		'03' => 'Mã checksum không chính xác, truy cập bị từ chối',
		'04' => 'Tên hàm API do merchant gọi tới không hợp lệ (không tồn tại)',
		'05' => 'Sai version của API',
		'07' => 'Sai mật khẩu của merchant',
		'08' => 'Địa chỉ email tài khoản nhận tiền không tồn tại',
		'09' => 'Tài khoản nhận tiền đang bị phong tỏa giao dịch',
		'10' => 'Mã đơn hàng không hợp lệ',
		'11' => 'Số tiền giao dịch lớn hơn hoặc nhỏ hơn quy định',
		'12' => 'Loại tiền tệ không hợp lệ',
		'29' => 'Token không tồn tại',
		'80' => 'Không thêm được đơn hàng',
		'81' => 'Đơn hàng chưa được thanh toán',
		'110' => 'Địa chỉ email tài khoản nhận tiền không phải email chính',
		'111' => 'Tài khoản nhận tiền đang bị khóa',
		'113' => 'Tài khoản nhận tiền chưa cấu hình là người bán nội dung số',
		'114' => 'Giao dịch đang thực hiện, chưa kết thúc',
		'115' => 'Giao dịch bị hủy',
		'118' => 'tax_amount không hợp lệ',
		'119' => 'discount_amount không hợp lệ',
		'120' => 'fee_shipping không hợp lệ',
		'121' => 'return_url không hợp lệ',
		'122' => 'cancel_url không hợp lệ',
		'123' => 'items không hợp lệ',
		'124' => 'transaction_info không hợp lệ',
		'125' => 'quantity không hợp lệ',
		'126' => 'order_description không hợp lệ',
		'127' => 'affiliate_code không hợp lệ',
		'128' => 'time_limit không hợp lệ',
		'129' => 'buyer_fullname không hợp lệ',
		'130' => 'buyer_email không hợp lệ',
		'131' => 'buyer_mobile không hợp lệ',
		'132' => 'buyer_address không hợp lệ',
		'133' => 'total_item không hợp lệ',
		'134' => 'payment_method, bank_code không hợp lệ',
		'135' => 'Lỗi kết nối tới hệ thống ngân hàng',
		'140' => 'Đơn hàng không hỗ trợ thanh toán trả góp',);
		return $arrCode[(string)$error_code];
	} 
	function getDomainRegister($url) {
		$pslManager = new PublicSuffixListManager(); 
		$parser = new Parser($pslManager->getList()); 
		$domainParser = $parser->parseUrl($url); 
		//echo $url; 
		return $domainParser->host->registerableDomain; 
	}
	function addNofollow($html, $skip = null,$linkJson=false) {
		if($linkJson==true){
			return preg_replace_callback(
				'/<a(.*?)href="(.*?)"([^>]*?)>/', function ($mach) use ($skip) {
					if(!($skip && strpos($mach[2], $skip) !== false) && strpos($mach[2], 'rel=') === false){
						return '<a class="siteLink" data-url='.htmlentities(json_encode($mach[2])).' href="javascript:void(0);">';
					}else{
						return $mach[0];
					}
				},
				$html
			);
		}else{
			return preg_replace_callback(
				"#(<a[^>]+?)>#is", function ($mach) use ($skip) {
					return (
						!($skip && strpos($mach[1], $skip) !== false) &&
						strpos($mach[1], 'rel=') === false
					) ? $mach[1] . ' rel="nofollow">' : $mach[0];
				},
				$html
			);
		}
	}
	function makeLinks($str) {
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$urls = array();
		$urlsToReplace = array();
		if(preg_match_all($reg_exUrl, $str, $urls)) {
			$numOfMatches = count($urls[0]);
			$numOfUrlsToReplace = 0;
			for($i=0; $i<$numOfMatches; $i++) {
				$alreadyAdded = false;
				$numOfUrlsToReplace = count($urlsToReplace);
				for($j=0; $j<$numOfUrlsToReplace; $j++) {
					if($urlsToReplace[$j] == $urls[0][$i]) {
						$alreadyAdded = true;
					}
				}
				if(!$alreadyAdded) {
					array_push($urlsToReplace, $urls[0][$i]);
				}
			}
			$numOfUrlsToReplace = count($urlsToReplace);
			for($i=0; $i<$numOfUrlsToReplace; $i++) {
				$str = str_replace($urlsToReplace[$i], "<a href=\"".$urlsToReplace[$i]."\">".$urlsToReplace[$i]."</a> ", $str);
			}
			return $str;
		} else {
			return $str;
		}
	}
	function limit_string($string, $charlimit) 
	{ 
		if(substr($string,$charlimit-1,1) != ' ') 
		{ 
			$string = substr($string,'0',$charlimit); 
			$array = explode(' ',$string); 
			array_pop($array); 
			$new_string = implode(' ',$array); 

			return $new_string.'...'; 
		} 
		else 
		{    
			return substr($string,'0',$charlimit-1).'...'; 
		} 
	} 
	public static function price($price)
	{
		if(is_numeric($price)){
			return number_format($price, 0);
		}else{
			return 0; 
		}
	}
	function formatBytesToMb($file) { 
		//$bytes = filesize($file);
		return number_format($file / 1048576, 0);
	}
	function formatBytes($size, $precision = 2) { 
		$base = log($size, 1024);
		$suffixes = array('', 'K', 'M', 'G', 'T');   

		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	} 
	public function getSEO($data = array()){
		$seo = array();
		$seo['title'] = $data['title'];
		$seo['keywords'] = $data['keywords'];
		$seo['description'] = $data['description'];
		$seo['og_type'] = $data['og_type'];
		$seo['og_site_name'] = $data['og_site_name'];
		$seo['og_title'] = $data['og_title'];
		$seo['og_description'] = $data['og_description'];
		$seo['og_url'] = $data['og_url'];
		$seo['og_img'] = $data['og_img'];
		$seo['current_url'] = $data['current_url'];
		return $seo;
	}
	public function getDomain($domain){
		$domain=Domain::where('domain','=',$domain)
		->first(); 
		if(empty($domain->domain)){
			return false; 
		}else{
			return $domain; 
		}
	}
    function time_request($time,$lang='')
    {
		if($lang=='en'){
			$minute=' minute ago'; 
			$hour=' hour ago'; 
			$day=' day ago'; 
			$month=' month ago'; 
			$year=' year ago'; 
		}else{
			$minute=' phút trước'; 
			$hour=' giờ trước'; 
			$day=' ngày trước'; 
			$month=' tháng trước'; 
			$year=' năm trước';
		}
        $date_current = date('Y-m-d H:i:s');
        $s = strtotime($date_current) - strtotime($time);
        if ($s <= 60) { // if < 60 seconds
            return '1 phút trước';
        }else
        {
            $t = intval($s / 60);
            if ($t >= 60) {
                $t = intval($t / 60);
                if ($t >= 24) {
                    $t = intval($t / 24);
                    if ($t >= 30) {
                        $t = intval($t / 30);
                        if ($t >= 12) {
                            $t = intval($t / 12);
                            return $t . $year;
                        } else {
                            return $t . $month;
                        }
                    } else {
                        return $t.$day;
                    }
                } else {
                    return $t.$hour;
                }
            } else {
                return $t.$minute;
            }
        }
    }
    function has_ssl( $domain ) {
        $ssl_check = @fsockopen( 'ssl://' . $domain, 443, $errno, $errstr, 30 );
        $res = !! $ssl_check;
        if ( $ssl_check ) { fclose( $ssl_check ); }
        return $res;
    }
	function checkBlacklistWord($str){
        $blacklist=preg_split("/(\r\n|\n|\r)/",File::get(public_path('data/words_blacklist.txt')));
        foreach($blacklist as $a) {
			if (stripos($str,$a) !== false) 
			{
				return false; 
			}
		}
		return true; 
	}
    function renameBlacklistWord($str){
        $blacklist=preg_split("/(\r\n|\n|\r)/",File::get(public_path('data/words_blacklist.txt')));
        return str_replace($blacklist, "***", $str);
    }
    function ConvertToUTF8Array($array){
	    if(is_array($array)){
            $out=array();
            foreach ($array as $text){
                $encoding = mb_detect_encoding($text, mb_detect_order(), false);

                if($encoding == "UTF-8")
                {
                    $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
                }
                array_push($out, @iconv(mb_detect_encoding($text, mb_detect_order(), false), "UTF-8//IGNORE", $text));
            }
            return $out;
        }else{
	        return $array;
        }
    }
    function detectUTF8($text){
        $enc = mb_detect_encoding($text, mb_list_encodings(), true);
        return $enc;
    }
    function convertToUTF8($text){
        $enc = mb_detect_encoding($text, mb_list_encodings(), true);
        if ($enc==false){
            $text=mb_strtolower($text, 'UTF-8');
        }
        else if ($enc!="UTF-8"){
            $text = @iconv($enc, "UTF-8//IGNORE", $text);
        }
        return $text;
    }
	function filter_by_value ($array, $index, $value){ 
        if(is_array($array) && count($array)>0)  
        { 
            foreach(array_keys($array) as $key){ 
                $temp[$key] = $array[$key][$index]; 
                 
                if ($temp[$key] == $value){ 
                    $newarray[$key] = $array[$key]; 
                } 
            } 
          } 
      return $newarray; 
    }
	function characterReplaceUrl($string){
		$string=strip_tags($string);
        $string=str_replace('/', 'Lw==', $string);
        $string=str_replace('\'', 'Jw==', $string);
        $string=str_replace('"', 'Ig==', $string);
        $string=str_replace(',', 'LA==', $string);
        $string=str_replace(';', 'Ow==', $string);
        $string=str_replace('<', 'PA==', $string);
        $string=str_replace('>', 'Pg==', $string);
        $string=str_replace('[', 'Ww==', $string);
        $string=str_replace(']', 'XQ==', $string);
        $string=str_replace('{', 'ew==', $string);
        $string=str_replace('}', 'fQ==', $string);
        $string=str_replace('|', 'fA==', $string);
        $string=str_replace('^', 'Xg==', $string);
        $string=str_replace('%', 'JQ==', $string);
        $string=str_replace('&', 'Jg==', $string);
        $string=str_replace('$', 'JA==', $string);
        $string=preg_replace('/([+])\\1+/', '$1',str_replace(' ','+',$string));
		return $string; 
	}
	public function keywordDecodeBase64($string){
        //$keywordnew=preg_replace('{(.)\1+}','$1',rtrim(str_replace('+', ' ', preg_replace('/[^\w\s]+/u',' ' ,$this->_parame['slug'])), '+'));
        $string=str_replace('Lw==', '/', $string);
        $string=str_replace('Jw==', '\'', $string);
        $string=str_replace('Ig==', '"', $string);
        $string=str_replace('LA==', ',', $string);
        $string=str_replace('Ow==', ';', $string);
        $string=str_replace('PA==', '<', $string);
        $string=str_replace('Pg==', '>', $string);
        $string=str_replace('Ww==', '[', $string);
        $string=str_replace('XQ==', ']', $string);
        $string=str_replace('ew==', '{', $string);
        $string=str_replace('fQ==', '}', $string);
        $string=str_replace('fA==', '|', $string);
        $string=str_replace('Xg==', '^', $string);
        $string=str_replace('JQ==', '%', $string);
        $string=str_replace('Jg==', '&', $string);
        $string=str_replace('JA==', '$', $string);
        return $string;
    }
	function isJson($string) {
	 json_decode($string);
	 return (json_last_error() == JSON_ERROR_NONE);
	}
	public function vn_str_filter ($str){
       $unicode = array(
           'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
           'd'=>'đ',
           'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
           'i'=>'í|ì|ỉ|ĩ|ị',
           'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
           'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
           'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
           'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
           'D'=>'Đ',
           'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
           'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
           'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
           'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
           'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
       );
		foreach($unicode as $nonUnicode=>$uni){
			$str = preg_replace("/($uni)/i", $nonUnicode, $str);
		}
		return $str;
	}
	function ODBCResourceToHTML($res, $sTable, $sRow)
	{
		$cFields = odbc_num_fields($res);
		$strTable = "<table $sTable ><tr>";  
		for ($n=1; $n<=$cFields; $n++)
		   {$strTable .= "<td $sRow><b>". str_replace("_", " ", odbc_field_name($res, $n)) . "</b></td>";}
		   $strTable .= "</tr>";
		   while(odbc_fetch_row($res))
		   { $strTable .= "<tr>";
			  for ($n=1; $n<=$cFields; $n++)
					 {$cell = odbc_result($res, $n);
			if ($cell=='') {$strTable .= "<td $sRow>&nbsp;</td>";}
					 else {$strTable .= "<td $sRow>". $cell . "</td>";}}
			 $strTable .= "</tr>";}
		$strTable .= "</table>";
		Return $strTable;
	}
	public function formatPhoneNumber($phoneNumber,$phone_prefix) {
		$phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);
		if(strlen($phoneNumber) > 10) {
			$countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-9);
			$areaCode = substr($phoneNumber, -10, 3);
			$nextThree = substr($phoneNumber, -7, 3);
			$lastFour = substr($phoneNumber, -4, 4);

			$phoneNumber = $phone_prefix.$areaCode.$nextThree.$lastFour;
		}
		else if(strlen($phoneNumber) > 9) {
			$countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-9);
			$areaCode = substr($phoneNumber, -9, 3);
			$nextThree = substr($phoneNumber, -6, 3);
			$lastFour = substr($phoneNumber, -3, 4);

			$phoneNumber = $phone_prefix.$areaCode.$nextThree.$lastFour;
		}
		else if(strlen($phoneNumber) < 9) {
			return false; 
		}

		return $phoneNumber;
	}
    function showMenuFields($menus,$menu_current,$id_parent = 0,$text="&nbsp;")
    {
       // dd($menus);
        // Biến lưu menu lặp ở bước đệ quy này
        $menu_tmp = array();
        foreach ($menus as $key => $item) {
            // Nếu có parent_id bằng với parrent id hiện tại
            if ((int) $item['parent_id'] == (int) $id_parent) {
                $menu_tmp[] = $item;
                unset($menus[$key]);
            }
        }
        // Điều kiện dừng của đệ quy là cho tới khi menu không còn nữa
        if (!empty($menu_tmp))
        {
			$txt='';
            foreach ($menu_tmp as $item)
            {
                if(!empty($menu_current)):
                    //for($i=0;$i<count($menu_current);$i++):
                    //$menu_currents=$menu_current[$i];
                    //dd($item['id']);
                    if(in_array($item['id'],$menu_current)):
                        //echo $item['id'];
                        //if($menu_currents['id']==$item['category_works_id']):
                        $txt.= '<option selected="selected" class="item_level_'.$item['categories_level'].'" value="'.$item['id'].'">';
                        $txt.= $text.$item['name'];
                        $txt.= '</option>';
                    else:
                        $txt.= '<option class="item_level_'.$item['categories_level'].'" value="'.$item['id'].'">';
                        $txt.= $text.$item['name'];
                        $txt.= '</option>';
                    endif;
                //endfor;
                else:
                    $txt.= '<option class="item_level_'.$item['categories_level'].'" value="'.$item['id'].'">';
                    $txt.= $text.$item['name'];
                    $txt.= '</option>';
                endif;
                // Truyền vào danh sách menu chưa lặp và id parent của menu hiện tại
               $this->showMenuFields($menus,$menu_current, $item['id'],$text.$text);

            }
			return $txt; 
        }
    }
    public  function objectEmpty($o)
    {
        if (empty($o)) return true;
        else if (is_numeric($o)) return false;
        else if (is_string($o)) return !strlen(trim($o));
        else if (is_object($o)) return $this->objectEmpty((array)$o);
        // It's an array!
        foreach($o as $element)
            if ($this->objectEmpty($element)) continue; // so far so good.
            else return false;

        // all good.
        return true;
    }
    public function is_valid_url($url) {
		// First check: is the url just a domain name? (allow a slash at the end)
		$_domain_regex = "|^[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,})/?$|";
		if (preg_match($_domain_regex, $url)) {
			return true;
		}

		// Second: Check if it's a url with a scheme and all
		$_regex = '#^([a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))$#';
		if (preg_match($_regex, $url, $matches)) {
			// pull out the domain name, and make sure that the domain is valid.
			$_parts = parse_url($url);
			if (!in_array($_parts['scheme'], array( 'http', 'https' )))
				return false;

			// Check the domain using the regex, stops domains like "-example.com" passing through
			if (!preg_match($_domain_regex, $_parts['host']))
				return false;

			// This domain looks pretty valid. Only way to check it now is to download it!
			return true;
		}

		return false;
	}
	function get_contents($url, $u = false, $c = null, $o = null) {
		$headers = get_headers($url);
		$status = substr($headers[0], 9, 3);
		if ($status == '200') {
			return file_get_contents($url, $u, $c, $o);
		}
		return false;
	}
	function get_data($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	function extract_tags( $html, $tag, $selfclosing = null, $return_the_entire_tag = false, $charset = 'ISO-8859-1' ){
		if ( is_array($tag) ){
			$tag = implode('|', $tag);
		}
		 
		//If the user didn't specify if $tag is a self-closing tag we try to auto-detect it
		//by checking against a list of known self-closing tags.
		$selfclosing_tags = array( 'area', 'base', 'basefont', 'br', 'hr', 'input', 'img', 'link', 'meta', 'col', 'param' );
		if ( is_null($selfclosing) ){
			$selfclosing = in_array( $tag, $selfclosing_tags );
		}
		 
		//The regexp is different for normal and self-closing tags because I can't figure out 
		//how to make a sufficiently robust unified one.
		if ( $selfclosing ){
			$tag_pattern = 
				'@<(?P<tag>'.$tag.')           # <tag
				(?P<attributes>\s[^>]+)?       # attributes, if any
				\s*/?>                   # /> or just >, being lenient here 
				@xsi';
		} else {
			$tag_pattern = 
				'@<(?P<tag>'.$tag.')           # <tag
				(?P<attributes>\s[^>]+)?       # attributes, if any
				\s*>                 # >
				(?P<contents>.*?)         # tag contents
				</(?P=tag)>               # the closing </tag>
				@xsi';
		}
		 
		$attribute_pattern = 
			'@
			(?P<name>\w+)                         # attribute name
			\s*=\s*
			(
				(?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)    # a quoted value
				|                           # or
				(?P<value_unquoted>[^\s"\']+?)(?:\s+|$)           # an unquoted value (terminated by whitespace or EOF) 
			)
			@xsi';
	 
		//Find all tags 
		if ( !preg_match_all($tag_pattern, $html, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE ) ){
			//Return an empty array if we didn't find anything
			return array();
		}
		 
		$tags = array();
		foreach ($matches as $match){
			 
			//Parse tag attributes, if any
			$attributes = array();
			if ( !empty($match['attributes'][0]) ){ 
				 
				if ( preg_match_all( $attribute_pattern, $match['attributes'][0], $attribute_data, PREG_SET_ORDER ) ){
					//Turn the attribute data into a name->value array
					foreach($attribute_data as $attr){
						if( !empty($attr['value_quoted']) ){
							$value = $attr['value_quoted'];
						} else if( !empty($attr['value_unquoted']) ){
							$value = $attr['value_unquoted'];
						} else {
							$value = '';
						}
						 
						//Passing the value through html_entity_decode is handy when you want
						//to extract link URLs or something like that. You might want to remove
						//or modify this call if it doesn't fit your situation.
						$value = html_entity_decode( $value, ENT_QUOTES, $charset );
						 
						$attributes[$attr['name']] = $value;
					}
				}
				 
			}
			 
			$tag = array(
				'tag_name' => $match['tag'][0],
				'offset' => $match[0][1], 
				'contents' => !empty($match['contents'])?$match['contents'][0]:'', //empty for self-closing tags
				'attributes' => $attributes, 
			);
			if ( $return_the_entire_tag ){
				$tag['full_tag'] = $match[0][0];            
			}
			  
			$tags[] = $tag;
		}
		 
		return $tags;
	}
	function unique_multidim_array($array, $key) { 
		$temp_array = array(); 
		$i = 0; 
		$key_array = array(); 
		
		foreach($array as $val) { 
			if (!in_array($val[$key], $key_array)) { 
				$key_array[$i] = $val[$key]; 
				$temp_array[$i] = $val; 
			} 
			$i++; 
		} 
		return $temp_array; 
	}
}//end class

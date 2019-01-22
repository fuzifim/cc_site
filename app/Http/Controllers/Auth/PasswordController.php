<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; 
use App\Http\Controllers\ConstructController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\User; 
use App\Model\Email;
use App\Model\Password_resets;
use WebService; 
use Redirect; 
use Route; 
use Theme; 
use Auth;
use Input;
use Validator; 
use Session; 
use Carbon\Carbon;
class PasswordController extends ConstructController
{
    use ResetsPasswords; 
	public function __construct(Guard $auth, PasswordBroker $passwords){
		$this->auth = $auth;
        $this->passwords = $passwords;
		parent::__construct(); 
	}
	public function forgotPassword()
	{
		$return = array();
		return $this->_theme->scope('admin.user.forgotpassword', $return)->render();
	}
	public function forgotPasswordRequest()
	{
		if(Session::has('forgot')){
			$sessionHistory=Session::get('forgot'); 
			if(!empty($sessionHistory['created_at'])){
				if(Carbon::parse($sessionHistory['created_at'])->addMinutes(5) > Carbon::now()->format('Y-m-d H:i:s')){
					$error='Mỗi yêu cầu phải cách nhau 5 phút. Lần yêu cầu gần đây nhất của bạn cách đây '.WebService::time_request($sessionHistory['created_at']); 
				}
			}
		}
		if(empty($error)){
			if(Session::has('forgot')){
				Session::forget('forgot');
			}
			Session::put('forgot', [ 
				'created_at' => Carbon::now()->format('Y-m-d H:i:s')
			]); 
			$email=Input::get('email'); 
			if(!empty($email)){
				$getEmail=Email::where('email_address','=',$email)->first(); 
				if(!empty($getEmail->email_address)){ 
					$response = $this->passwords->sendResetLink(['email'=>$getEmail->email_address], function($m) {
						$m->from(config('app.app_email'), $this->_channel->channel_name); 
						if(isset($this->_channel->emailJoin->email)){
							$m->replyTo($this->_channel->emailJoin->email->email_address, $this->_channel->channel_name); 
						}
						$m->subject('Yêu cầu thay đổi mật khẩu');
					});
					switch ($response) {
						case PasswordBroker::RESET_LINK_SENT:
							return response()->json(['success'=>true, 
								'message'=>'Đã gửi mật khẩu tới địa chỉ '.$getEmail->email_address, 
							]);
						case PasswordBroker::INVALID_USER:
							return response()->json(['success'=>true, 
								'message'=>trans($response), 
							]);
					}
				}else{
					return response()->json(['success'=>false, 
						'message'=>'Địa chỉ '.$email.' không tồn tại', 
					]);
				}
			}else{
				return response()->json(['success'=>false, 
					'message'=>'Bạn chưa nhập địa chỉ Email! ', 
				]);
			}
		}else{
			return response()->json(['success'=>false, 
				'message'=>$error, 
			]);
		}
	}
    public function getReset()
    {
        if (is_null($this->_parame['token']))
        {
            throw new NotFoundHttpException;
        }
		$getInfoToken=Password_resets::where('token','=',$this->_parame['token'])->first(); 
		if(!empty($getInfoToken->email)){
			$return = array(
				'token'=>$this->_parame['token'], 
				'infoToken'=>$getInfoToken
			); 
			return $this->_theme->scope('admin.user.forgotPasswordReset', $return)->render();
		}else{
			$view = array(); 
			return $this->_theme->scope('404', $view)->render(); 
		}
    }
    public function postReset(Request $request)
    {
		$email=Input::get('email'); 
		$password=Input::get('password'); 
		$password_confirmation=Input::get('password_confirmation'); 
		$token=Input::get('token'); 
		if($password==$password_confirmation){
			$credentials = ['email'=>$email, 'password'=>$password, 'password_confirmation'=>$password_confirmation, 'token'=>$token]; 
			$response = $this->passwords->reset($credentials, function($user, $password)
			{
				$user->password = bcrypt($password);
				$user->save();
				$this->auth->login($user);
			});
			switch ($response)
			{
				case PasswordBroker::PASSWORD_RESET:
					return response()->json(['success'=>true, 
						'message'=>'Đổi mật khẩu thành công!', 
					]);
				default:
					return response()->json(['success'=>false, 
						'type'=>'error', 
						'message'=>'Đổi mật khẩu không thành công!', 
					]);
			}
		}else{
			return response()->json(['success'=>false, 
				'type'=>'error', 
				'message'=>'Mật khẩu nhập lại không đúng! ', 
			]);
		}
    }
}

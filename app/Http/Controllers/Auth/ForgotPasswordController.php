<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\ConstructController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Theme;
use Session;
use Carbon\Carbon;
use WebService;
class ForgotPasswordController extends ConstructController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    use SendsPasswordResetEmails;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        parent::__construct();
    }
    public function showLinkRequestForm()
    {
        return Theme::view('admin.user.forgotpassword',[]);
    }
    public function sendResetLinkEmail(Request $request)
    {
        if(Session::has('forgot')){
            $sessionHistory=Session::get('forgot');
            if(!empty($sessionHistory['created_at'])){
                if(Carbon::parse($sessionHistory['created_at'])->addMinutes(5) > Carbon::now()->format('Y-m-d H:i:s')){
                    $error='Mỗi yêu cầu phải cách nhau 5 phút. Lần yêu cầu gần đây nhất của bạn cách đây '. WebService::time_request($sessionHistory['created_at']);
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

            $this->validateEmail($request);
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );
            return $response == Password::RESET_LINK_SENT
                ? $this->sendResetLinkResponse($request, $response)
                : $this->sendResetLinkFailedResponse($request, $response);
        }else{
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => $error]);
        }
    }
}
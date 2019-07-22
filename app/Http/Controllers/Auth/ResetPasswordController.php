<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\ConstructController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Theme;
class ResetPasswordController extends ConstructController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    use ResetsPasswords;
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';
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

    public function showResetForm(Request $request, $token = null)
    {
        return Theme::view('admin.user.forgotPasswordReset',['token' => $request->token, 'email' => $request->email]);
    }
}
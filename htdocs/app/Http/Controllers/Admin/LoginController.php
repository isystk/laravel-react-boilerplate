<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    protected $maxAttempts = 5; // 5回失敗したらロックする
    protected $decayMinutes = 30; // ロックは30分間

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Validate the user login request.
     *
     * @param Request $request
     * @return void
     *
     */
    protected function validateLogin(Request $request): void
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        $requests = $request->all();
        $rules = [
            'g-recaptcha-response' => ['required',new CaptchaRule]
        ];
        $messages = [
            'g-recaptcha-response.captcha' => 'reCaptchaによってうまく認証されませんでした'
        ];
        Validator::make($requests, $rules, $messages)->validate();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('/admin/login');
    }

    /**
     * ログイン試行回数のアカウントロックはIP単位にする
     * @param Request $request
     * @return mixed|string
     */
    protected function throttleKey(Request $request): string
    {
        // プロキシサーバを経由した場合は、X-Forwarded-Forヘッダから接続元のクライアントIPを取得
        return $_SERVER["HTTP_X_FORWARDED_FOR"] ?? $request->ip();
    }
}

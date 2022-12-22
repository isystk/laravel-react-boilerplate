<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string|min:10',
            'password' => 'required|string',
        ]);

        $requests = $request->all();
        $rules = [
            // reCaptchaによる認証チェックはコメントアウトしておく
//            'g-recaptcha-response' => ['required',new CaptchaRule]
        ];
        $messages = [
            'g-recaptcha-response.captcha' => 'reCaptchaによってうまく認証されませんでした'
        ];
        Validator::make($requests, $rules, $messages)->validate();
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

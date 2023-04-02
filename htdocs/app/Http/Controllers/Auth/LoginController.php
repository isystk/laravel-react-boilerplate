<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    protected int $maxAttempts = 5; // 5回失敗したらロックする
    protected int $decayMinutes = 30; // ロックは30分間

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    protected function validateLogin(Request $request): void
    {
        $this->validate($request, [
            $this->username() => 'required|string|min:10',
            'password' => 'required|string',
            // reCaptchaによる認証チェックはコメントアウトしておく
//            'g-recaptcha-response' => 'required|recaptchav3:,0.5'
        ]);
    }

    /**
     * ログイン試行回数のアカウントロックはIP単位にする
     * @param Request $request
     * @return string
     */
    protected function throttleKey(Request $request): string
    {
        // プロキシサーバを経由した場合は、X-Forwarded-Forヘッダから接続元のクライアントIPを取得
        return $_SERVER["HTTP_X_FORWARDED_FOR"] ?? $request->ip();
    }
}

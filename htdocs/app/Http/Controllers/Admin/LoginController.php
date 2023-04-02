<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('admin.login');
    }

    /**
     * @return StatefulGuard
     */
    protected function guard(): StatefulGuard
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
            'email' => 'required|string',
            'password' => 'required|string',
            // reCaptchaによる認証チェックはコメントアウトしておく
//            'g-recaptcha-response' => 'required|recaptchav3:login,0.5'
        ]);
    }

    public function login(Request $request) {

        $this->validateLogin($request);

        $credentials = $request->only(['email', 'password']);

        if($this->guard()->attempt($credentials)) {

            return redirect('/admin/home'); // ログインしたらリダイレクト

        }

        return back()->withErrors([
            'auth' => ['認証に失敗しました']
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect($this->redirectTo);
    }

}

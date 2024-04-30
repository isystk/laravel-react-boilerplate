<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginBaseController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 未ログインのユーザーのみアクセスを許可する（logout関数を除く）
        $this->middleware('guest:admin')
            ->except('logout');
    }

    /**
     * ログイン画面の初期表示
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('admin.login');
    }

    /**
     * ログイン画面の初期表示
     * @return StatefulGuard
     */
    protected function guard(): StatefulGuard
    {
        return Auth::guard('admin');
    }

    /**
     * ログイン画面のログインチェック
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

    /**
     * ログイン画面のログイン処理
     *
     * @param Request $request
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function login(Request $request): \Illuminate\Foundation\Application|Redirector|Application|RedirectResponse
    {
        // ログインチェック
        $this->validateLogin($request);

        $credentials = $request->only(['email', 'password']);

        if (Auth::guard('admin')->attempt($credentials)) {
            // ログインが成功したら、ホーム画面にリダイレクトする
            return redirect(route('admin.home'));
        }

        // 認証に失敗した場合は、元画面にエラーを表示する。
        return back()->withErrors([
            'auth' => ['認証に失敗しました'],
        ]);
    }

    /**
     * ログアウト処理
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        // ログアウト処理
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        // ログアウト後は、ログイン画面にリダイレクトする
        return redirect(route('admin.login'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends BaseController
{
    /**
     * ログイン画面の初期表示
     */
    public function index(): RedirectResponse|View
    {
        if (Auth::guard('admin')->check()) {
            // ログイン済みの場合

            // ホーム画面にリダイレクト
            return redirect(route('admin.home'));
        }

        return view('admin.login');
    }

    /**
     * ログイン画面のログイン処理
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only(['email', 'password']);
        if (!Auth::guard('admin')->attempt($credentials)) {
            // 認証に失敗した場合
            return back()->withErrors([
                'password' => ['認証に失敗しました'],
            ]);
        }
        // ログインが成功した場合

        // ホーム画面にリダイレクト
        return redirect(route('admin.home'));
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        // ログイン画面にリダイレクト
        return redirect(route('admin.login'));
    }
}

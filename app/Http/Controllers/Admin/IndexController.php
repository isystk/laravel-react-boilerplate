<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;

class IndexController extends BaseController
{
    /**
     * ルートへのアクセス
     */
    public function index(): RedirectResponse
    {
        if (auth('admin')->guest()) {
            // ログイン画面にリダイレクト
            return redirect(route('admin.login'));
        }

        // ホーム画面にリダイレクト
        return redirect(route('admin.home'));
    }
}

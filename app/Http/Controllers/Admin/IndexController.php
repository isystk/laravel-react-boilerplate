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
        // ホーム画面にリダイレクト
        return redirect(route('admin.home'));
    }
}

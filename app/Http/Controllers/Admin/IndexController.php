<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;

class IndexController extends BaseController
{

    /**
     * トップ画面にアクセス
     */
    public function index(): RedirectResponse
    {
        return redirect(route('admin.home'));
    }
}

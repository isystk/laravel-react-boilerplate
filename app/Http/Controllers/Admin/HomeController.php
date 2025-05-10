<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\View\View;

class HomeController extends BaseController
{
    /**
     * ホーム画面の初期表示
     */
    public function index(): View
    {
        return view('admin.home');
    }
}

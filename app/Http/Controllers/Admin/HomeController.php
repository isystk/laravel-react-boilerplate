<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * ホーム画面の初期表示
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('admin.home');
    }
}

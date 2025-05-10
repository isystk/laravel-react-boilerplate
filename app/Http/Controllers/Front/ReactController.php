<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\View\View;

class ReactController extends BaseController
{
    /**
     * フロントの初期表示（表示後はReactのRouterに任せる）
     */
    public function index(): View
    {
        return view('front.react');
    }

}

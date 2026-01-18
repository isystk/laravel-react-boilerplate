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
        // 最新の注文を取得
        $latestOrders = [];

        return view('admin.home',
            compact([
                'latestOrders',
            ])
        );
    }
}

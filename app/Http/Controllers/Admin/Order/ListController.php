<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\BaseController;
use App\Services\Admin\Order\IndexService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListController extends BaseController
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
     * 注文履歴一覧画面の初期表示
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);
        $orders = $service->searchOrder();

        return view('admin.order.index', compact('orders', 'request'));
    }
}

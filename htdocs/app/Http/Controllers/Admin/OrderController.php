<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Entities\Order;
use App\Http\Controllers\BaseController;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends BaseController
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
        $service = app(OrderService::class);
        $orders = $service->list();

        return view('admin.order.index', compact('orders', 'request'));
    }

    /**
     * 注文履歴詳細画面の初期表示
     *
     * @param Order $order
     * @return View
     */
    public function show(Order $order): View
    {
        return view('admin.order.show', compact('order'));
    }
}

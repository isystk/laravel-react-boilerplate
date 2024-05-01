<?php

namespace App\Http\Controllers\Admin\Order;

use App\Domain\Entities\Order;
use App\Http\Controllers\BaseController;
use Illuminate\View\View;

class DetailController extends BaseController
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

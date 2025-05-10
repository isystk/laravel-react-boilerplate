<?php

namespace App\Http\Controllers\Admin\Order;

use App\Domain\Entities\Order;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Order\ShowService;
use Illuminate\View\View;

class DetailController extends BaseController
{

    /**
     * 注文履歴詳細画面の初期表示
     */
    public function show(Order $order): View
    {
        /** @var ShowService $service */
        $service = app(ShowService::class);
        $orderStocks = $service->getOrderStock($order->id);

        return view('admin.order.show', compact('order', 'orderStocks'));
    }
}

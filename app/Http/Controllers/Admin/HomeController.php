<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\Home\IndexService;
use Illuminate\View\View;

class HomeController extends BaseController
{
    /**
     * ホーム画面の初期表示
     */
    public function index(): View
    {
        $service = app(IndexService::class);

        $todaysOrdersCount = $service->getTodaysOrdersCount();
        $salesByMonth      = $service->getSalesByMonth();
        $latestOrders      = $service->getLatestOrders();

        return view('admin.home',
            compact([
                'todaysOrdersCount',
                'salesByMonth',
                'latestOrders',
            ])
        );
    }
}

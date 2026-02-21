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

        $unrepliedContactsCount = $service->getUnrepliedContactsCount();
        $salesByMonth           = $service->getSalesByMonth();
        $latestOrders           = $service->getLatestOrders();
        $usersByMonth           = $service->getUsersByMonth();

        return view('admin.home',
            compact([
                'unrepliedContactsCount',
                'salesByMonth',
                'latestOrders',
                'usersByMonth',
            ])
        );
    }
}

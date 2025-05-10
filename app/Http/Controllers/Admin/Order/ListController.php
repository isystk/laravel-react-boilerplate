<?php

namespace App\Http\Controllers\Admin\Order;

use App\Dto\Request\Admin\Order\SearchConditionDto;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Order\IndexService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListController extends BaseController
{
    /**
     * 注文履歴一覧画面の初期表示
     */
    public function index(Request $request): View
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);

        $searchCondition = new SearchConditionDto($request);
        $orders = $service->searchOrder($searchCondition);

        return view('admin.order.index', compact('orders', 'request'));
    }
}

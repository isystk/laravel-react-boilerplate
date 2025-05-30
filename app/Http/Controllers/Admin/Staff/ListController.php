<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Http\Controllers\BaseController;
use App\Services\Admin\Staff\IndexService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListController extends BaseController
{
    /**
     * スタッフ一覧画面の初期表示
     */
    public function index(Request $request): View
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);

        $conditions = $service->convertConditionsFromRequest($request);
        $staffs = $service->searchStaff($conditions);

        return view('admin.staff.index', compact([
            'staffs',
        ]));
    }
}

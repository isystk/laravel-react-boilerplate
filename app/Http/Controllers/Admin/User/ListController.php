<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\BaseController;
use App\Services\Admin\User\IndexService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListController extends BaseController
{

    /**
     * 顧客一覧画面の初期表示
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);

        $conditions = $service->convertConditionsFromRequest($request);
        $users = $service->searchUser($conditions);

        return view('admin.user.index', compact('users', 'request'));
    }

}

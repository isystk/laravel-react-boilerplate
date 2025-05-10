<?php

namespace App\Http\Controllers\Admin\User;

use App\Dto\Request\Admin\User\SearchConditionDto;
use App\Http\Controllers\BaseController;
use App\Services\Admin\User\IndexService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListController extends BaseController
{

    /**
     * 顧客一覧画面の初期表示
     */
    public function index(Request $request): View
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);

        $conditions = new SearchConditionDto($request);
        $users = $service->searchUser($conditions);

        return view('admin.user.index', compact('users', 'request'));
    }

}

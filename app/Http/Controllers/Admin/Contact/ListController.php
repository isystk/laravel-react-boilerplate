<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Dto\Request\Admin\Contact\SearchConditionDto;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Contact\IndexService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListController extends BaseController
{
    /**
     * お問い合わせ一覧の初期表示
     */
    public function index(Request $request): View
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);

        $conditions = new SearchConditionDto($request);
        $contacts   = $service->searchContact($conditions);

        return view('admin.contact.index', compact([
            'contacts',
        ]));
    }
}

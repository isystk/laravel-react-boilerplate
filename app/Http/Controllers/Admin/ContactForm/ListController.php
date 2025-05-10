<?php

namespace App\Http\Controllers\Admin\ContactForm;

use App\Dto\Request\Admin\ContactForm\SearchConditionDto;
use App\Http\Controllers\BaseController;
use App\Services\Admin\ContactForm\IndexService;
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
        $contactForms = $service->searchContactForm($conditions);

        return view('admin.contact.index', compact('contactForms', 'request'));
    }
}

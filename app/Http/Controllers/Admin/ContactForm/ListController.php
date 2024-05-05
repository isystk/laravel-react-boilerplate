<?php

namespace App\Http\Controllers\Admin\ContactForm;

use App\Http\Controllers\BaseController;
use App\Services\Admin\ContactForm\IndexService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListController extends BaseController
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
     * お問い合わせ一覧の初期表示
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);
        $contactForms = $service->searchContactForm();

        return view('admin.contact.index', compact('contactForms', 'request'));
    }
}

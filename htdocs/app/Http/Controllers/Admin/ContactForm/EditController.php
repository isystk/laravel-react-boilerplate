<?php

namespace App\Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreContactFormRequest;
use App\Services\Api\ContactForm\StoreService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EditController extends BaseController
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
     * お問い合わせ変更画面の初期表示
     *
     * @param ContactForm $contact
     * @return View
     */
    public function edit(ContactForm $contact): View
    {
        return view('admin.contact.edit', compact('contact'));
    }

    /**
     * お問い合わせ変更画面の登録処理
     *
     * @param StoreContactFormRequest $request
     * @param ContactForm $contact
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(StoreContactFormRequest $request, ContactForm $contact): RedirectResponse
    {
        DB::beginTransaction();
        try {
            /** @var StoreService $service */
            $service = app(StoreService::class);
            $service->save($contact->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.contact'));
    }
}

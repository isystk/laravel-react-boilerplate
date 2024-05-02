<?php

namespace App\Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ContactForm\UpdateRequest;
use App\Http\Requests\StoreContactFormRequest;
use App\Services\Admin\ContactForm\EditService;
use App\Services\Admin\ContactForm\UpdateService;
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
     * @param ContactForm $contactForm
     * @return View
     */
    public function edit(ContactForm $contactForm): View
    {
        /** @var EditService $service */
        $service = app(EditService::class);
        $contactFormImages = $service->getContactFormImage($contactForm->id);

        return view('admin.contact.edit', compact('contactForm', 'contactFormImages'));
    }

    /**
     * お問い合わせ変更画面の登録処理
     *
     * @param UpdateRequest $request
     * @param ContactForm $contactForm
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(UpdateRequest $request, ContactForm $contactForm): RedirectResponse
    {
        DB::beginTransaction();
        try {
            /** @var UpdateService $service */
            $service = app(UpdateService::class);
            $service->update($contactForm->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.contact'));
    }
}

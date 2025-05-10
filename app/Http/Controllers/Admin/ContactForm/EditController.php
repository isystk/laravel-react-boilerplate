<?php

namespace App\Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ContactForm\UpdateRequest;
use App\Services\Admin\ContactForm\EditService;
use App\Services\Admin\ContactForm\UpdateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class EditController extends BaseController
{
    /**
     * お問い合わせ変更画面の初期表示
     */
    public function edit(ContactForm $contactForm): View
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');

        /** @var EditService $service */
        $service = app(EditService::class);
        $contactFormImages = $service->getContactFormImage($contactForm->id);

        return view('admin.contact.edit', compact('contactForm', 'contactFormImages'));
    }

    /**
     * お問い合わせ変更画面の登録処理
     *
     * @throws Throwable
     */
    public function update(UpdateRequest $request, ContactForm $contactForm): RedirectResponse
    {
        /** @var UpdateService $service */
        $service = app(UpdateService::class);

        DB::beginTransaction();
        try {
            $service->update($contactForm->id, $request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect(route('admin.contact'));
    }
}

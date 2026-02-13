<?php

namespace App\Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Http\Controllers\BaseController;
use App\Services\Admin\ContactForm\DestroyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class DetailController extends BaseController
{
    /**
     * お問い合わせ詳細画面の初期表示
     */
    public function show(ContactForm $contactForm): View
    {
        return view('admin.contact.show', compact([
            'contactForm',
        ]));
    }

    /**
     * お問い合わせ詳細画面の削除処理
     *
     * @throws Throwable
     */
    public function destroy(ContactForm $contactForm): RedirectResponse
    {
        /** @var DestroyService $service */
        $service = app(DestroyService::class);

        DB::beginTransaction();

        try {
            $service->delete($contactForm->id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return redirect(route('admin.contact'));
    }
}

<?php

namespace App\Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Http\Controllers\BaseController;
use App\Services\Admin\ContactForm\DestroyService;
use App\Services\Admin\ContactForm\ShowService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DetailController extends BaseController
{

    /**
     * お問い合わせ詳細画面の初期表示
     *
     * @param ContactForm $contactForm
     * @return View
     */
    public function show(ContactForm $contactForm): View
    {
        /** @var ShowService $service */
        $service = app(ShowService::class);
        $contactFormImages = $service->getContactFormImage($contactForm->id);

         return view('admin.contact.show', compact('contactForm', 'contactFormImages'));
    }

    /**
     * お問い合わせ詳細画面の削除処理
     * Remove the specified resource from storage.
     *
     * @param ContactForm $contactForm
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(ContactForm $contactForm): RedirectResponse
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');
        DB::beginTransaction();
        try {
            /** @var DestroyService $service */
            $service = app(DestroyService::class);
            $service->delete($contactForm->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.contact'));
    }
}

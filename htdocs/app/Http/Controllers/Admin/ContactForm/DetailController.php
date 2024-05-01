<?php

namespace App\Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Http\Controllers\BaseController;
use App\Services\Api\ContactForm\StoreService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DetailController extends BaseController
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
     * お問い合わせ詳細画面の初期表示
     *
     * @param ContactForm $contact
     * @return View
     */
    public function show(ContactForm $contact): View
    {
         return view('admin.contact.show', compact('contact'));
    }

    /**
     * お問い合わせ詳細画面の削除処理
     * Remove the specified resource from storage.
     *
     * @param ContactForm $contact
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(ContactForm $contact): RedirectResponse
    {
        DB::beginTransaction();
        try {
            /** @var StoreService $service */
            $service = app(StoreService::class);
            $service->delete($contact->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.contact'));
    }
}

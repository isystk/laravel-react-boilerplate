<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Domain\Entities\Contact;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Contact\DestroyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class DetailController extends BaseController
{
    /**
     * お問い合わせ詳細画面の初期表示
     */
    public function show(Contact $contact): View
    {
        return view('admin.contact.show', compact([
            'contact',
        ]));
    }

    /**
     * お問い合わせ詳細画面の削除処理
     *
     * @throws Throwable
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        /** @var DestroyService $service */
        $service = app(DestroyService::class);

        DB::beginTransaction();

        try {
            $service->delete($contact->id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return redirect(route('admin.contact'));
    }
}

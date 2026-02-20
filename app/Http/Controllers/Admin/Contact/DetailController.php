<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Domain\Entities\Contact;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Contact\ReplyRequest;
use App\Services\Admin\Contact\DestroyService;
use App\Services\Admin\Contact\ReplyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
        $contact->load(['replies.admin']);

        return view('admin.contact.show', compact([
            'contact',
        ]));
    }

    /**
     * お問い合わせへの返信処理
     *
     * @throws Throwable
     */
    public function reply(ReplyRequest $request, Contact $contact): RedirectResponse
    {
        /** @var ReplyService $service */
        $service = app(ReplyService::class);

        /** @var \App\Domain\Entities\Admin $admin */
        $admin = Auth::guard('admin')->user();

        DB::beginTransaction();

        try {
            $service->reply($contact, $admin->id, $request->string('body')->toString());
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return redirect(route('admin.contact.show', ['contact' => $contact]))
            ->with('success', '返信を送信しました。');
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

<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Domain\Entities\Contact;
use App\Dto\Request\Admin\Contact\UpdateDto;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Contact\UpdateRequest;
use App\Services\Admin\Contact\UpdateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class EditController extends BaseController
{
    /**
     * お問い合わせ変更画面の初期表示
     */
    public function edit(Contact $contact): View
    {
        return view('admin.contact.edit', compact([
            'contact',
        ]));
    }

    /**
     * お問い合わせ変更画面の登録処理
     *
     * @throws Throwable
     */
    public function update(UpdateRequest $request, Contact $contact): RedirectResponse
    {
        /** @var UpdateService $service */
        $service = app(UpdateService::class);

        DB::beginTransaction();

        $dto = new UpdateDto($request);

        try {
            $service->update($contact, $dto);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return redirect(route('admin.contact.show', $contact));
    }
}

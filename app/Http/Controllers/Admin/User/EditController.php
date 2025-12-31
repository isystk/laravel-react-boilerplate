<?php

namespace App\Http\Controllers\Admin\User;

use App\Domain\Entities\User;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Services\Admin\User\UpdateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class EditController extends BaseController
{
    /**
     * 顧客変更画面の初期表示
     */
    public function edit(User $user): View
    {
        return view('admin.user.edit', compact([
            'user',
        ]));
    }

    /**
     * 顧客変更画面の登録処理
     *
     * @throws Throwable
     */
    public function update(UpdateRequest $request, User $user): RedirectResponse
    {
        /** @var UpdateService $service */
        $service = app(UpdateService::class);

        DB::beginTransaction();
        try {
            $service->update($user->id, $request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect(route('admin.user.show', $user));
    }
}

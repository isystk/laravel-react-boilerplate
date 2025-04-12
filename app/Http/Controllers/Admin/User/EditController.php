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
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');
        return view('admin.user.edit', compact('user'));
    }

    /**
     * 顧客変更画面の登録処理
     *
     * @param UpdateRequest $request
     * @param User $user
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(UpdateRequest $request, User $user): RedirectResponse
    {
        DB::beginTransaction();
        try {
            /** @var UpdateService $service */
            $service = app(UpdateService::class);
            $service->update($user->id, $request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.user'));
    }

}

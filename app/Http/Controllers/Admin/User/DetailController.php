<?php

namespace App\Http\Controllers\Admin\User;

use App\Domain\Entities\User;
use App\Http\Controllers\BaseController;
use App\Services\Admin\User\DestroyService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class DetailController extends BaseController
{
    /**
     * 顧客詳細画面の初期表示
     */
    public function show(User $user): View
    {
        return view('admin.user.show', compact([
            'user',
        ]));
    }

    /**
     * 顧客詳細画面の削除処理
     *
     * @throws Throwable
     */
    public function destroy(User $user): RedirectResponse
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');

        /** @var DestroyService $service */
        $service = app(DestroyService::class);

        DB::beginTransaction();
        try {
            $service->delete($user->id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect(route('admin.user'));
    }
}

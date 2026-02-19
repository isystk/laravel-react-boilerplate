<?php

namespace App\Http\Controllers\Admin\User;

use App\Domain\Entities\User;
use App\Http\Controllers\BaseController;
use App\Services\Admin\User\SuspendService;
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
     * 顧客詳細画面のアカウント停止処理
     *
     * @throws Throwable
     */
    public function suspend(User $user): RedirectResponse
    {
        /** @var SuspendService $service */
        $service = app(SuspendService::class);

        DB::beginTransaction();

        try {
            $service->suspend($user->id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return redirect(route('admin.user.show', ['user' => $user]));
    }

    /**
     * 顧客詳細画面のアカウント有効化処理
     *
     * @throws Throwable
     */
    public function activate(User $user): RedirectResponse
    {
        /** @var SuspendService $service */
        $service = app(SuspendService::class);

        DB::beginTransaction();

        try {
            $service->activate($user->id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return redirect(route('admin.user.show', ['user' => $user]));
    }
}

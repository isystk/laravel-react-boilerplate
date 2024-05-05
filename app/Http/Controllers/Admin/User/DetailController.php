<?php

namespace App\Http\Controllers\Admin\User;

use App\Domain\Entities\User;
use App\Http\Controllers\BaseController;
use App\Services\Admin\User\DestroyService;
use App\Services\Admin\User\IndexService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

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
     * 顧客詳細画面の初期表示
     *
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        return view('admin.user.show', compact('user'));
    }

    /**
     * 顧客詳細画面の削除処理
     *
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user): RedirectResponse
    {
        DB::beginTransaction();
        try {
            /** @var DestroyService $service */
            $service = app(DestroyService::class);
            $service->delete($user->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.user'));
    }
}

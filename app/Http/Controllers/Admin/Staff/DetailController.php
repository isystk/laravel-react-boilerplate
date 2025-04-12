<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Staff\DestroyService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Throwable;

class DetailController extends BaseController
{

    /**
     * スタッフ詳細画面の初期表示
     *
     * @param Admin $staff
     * @return View
     */
    public function show(Admin $staff): View
    {
        return view('admin.staff.show', compact('staff'));
    }

    /**
     * スタッフ詳細画面の削除処理
     *
     * @param Admin $staff
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Admin $staff): RedirectResponse
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');
        if (auth()->id() === $staff->id) {
            $errors = new MessageBag;
            $errors->add('errors', '自分自身を削除することはできません');
            return back()->withErrors($errors);
        }

        DB::beginTransaction();
        try {
            /** @var DestroyService $service */
            $service = app(DestroyService::class);
            $service->delete($staff->id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.staff'));
    }
}

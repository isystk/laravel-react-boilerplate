<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Staff\DestroyService;
use App\Services\Common\OperationLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Throwable;

class DetailController extends BaseController
{
    public function __construct(private readonly OperationLogService $operationLogService) {}

    /**
     * スタッフ詳細画面の初期表示
     */
    public function show(Admin $staff): View
    {
        $operationLogs = $this->operationLogService->getAdminLogs($staff->id);

        return view('admin.staff.show', compact('staff', 'operationLogs'));
    }

    /**
     * スタッフ詳細画面の削除処理
     *
     * @throws Throwable
     */
    public function destroy(Admin $staff): RedirectResponse
    {
        if (auth()->id() === $staff->id) {
            $errors = new MessageBag;
            $errors->add('errors', '自分自身を削除することはできません');

            return back()->withErrors($errors);
        }

        /** @var DestroyService $service */
        $service = app(DestroyService::class);

        DB::beginTransaction();

        try {
            $service->delete($staff->id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return redirect(route('admin.staff'));
    }
}

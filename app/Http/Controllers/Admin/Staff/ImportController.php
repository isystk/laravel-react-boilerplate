<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Http\Controllers\BaseController;
use App\Services\Admin\Staff\Import\ExportService;
use App\Services\Admin\Staff\Import\RegistService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class ImportController extends BaseController
{

    /**
     * スタッフ一括インポート画面の初期表示
     *
     * @return View
     */
    public function index(): View
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');

        return view('admin.staff.import');
    }

    /**
     * スタッフ一括インポート画面のエクスポート処理
     *
     * @return RedirectResponse
     * @throws Throwable
     */
    public function export(): RedirectResponse
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');

        DB::beginTransaction();
        try {
            /** @var ExportService $service */
            $service = app(ExportService::class);
            $data = $service->export();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.staff.import'));
    }

    /**
     * スタッフ一括インポート画面のインポート処理
     *
     * @return RedirectResponse
     * @throws Throwable
     */
    public function regist(): RedirectResponse
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');

        DB::beginTransaction();
        try {
            /** @var RegistService $service */
            $service = app(RegistService::class);
            $service->import();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.staff.import'));
    }
}

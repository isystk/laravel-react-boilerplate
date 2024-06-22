<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Staff\Import\StoreRequest;
use App\Services\Admin\Staff\Import\ExportService;
use App\Services\Admin\Staff\Import\ImportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request): BinaryFileResponse
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');

        $fileType = $request->file_type;
        if (!in_array($fileType, ['csv', 'xlsx'])) {
            abort(400);
        }

        /** @var ExportService $service */
        $service = app(ExportService::class);
        $export = $service->getExport();

        return ('csv' === $fileType) ?
            Excel::download($export, 'staff.csv', \Maatwebsite\Excel\Excel::CSV):
            Excel::download($export, 'staff.xlsx');
    }

    /**
     * スタッフ一括インポート画面のインポート処理
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');

        DB::beginTransaction();
        try {
            /** @var ImportService $service */
            $service = app(ImportService::class);
            $service->createJob($request->upload_file);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.staff.import'))->with('success', 'ファイルのインポートに成功しました。');
    }
}

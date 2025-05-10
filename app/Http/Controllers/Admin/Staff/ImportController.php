<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Staff\Import\StoreRequest;
use App\Services\Admin\Staff\Import\ExportService;
use App\Services\Admin\Staff\Import\ImportFileService;
use App\Services\Admin\Staff\Import\ImportService;
use App\Services\Admin\Staff\Import\IndexService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ImportController extends BaseController
{

    /**
     * スタッフ一括インポート画面の初期表示
     */
    public function index(): View
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');

        /** @var IndexService $service */
        $service = app(IndexService::class);
        $importHistories = $service->getImportHistories();

        return view('admin.staff.import', compact('importHistories'));
    }

    /**
     * スタッフ一括インポート画面のエクスポート処理
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
            Excel::download($export, 'staff.csv', \Maatwebsite\Excel\Excel::CSV) :
            Excel::download($export, 'staff.xlsx');
    }

    /**
     * スタッフ一括インポート画面のインポート処理
     * @throws Throwable
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');

        if (!$request->upload_file instanceof UploadedFile) {
            throw ValidationException::withMessages(['import_file' => 'An unexpected error has occurred.']);
        }
        $admin = $request->user();

        /** @var ImportService $service */
        $service = app(ImportService::class);

        DB::beginTransaction();
        try {
            $service->createJob($request->upload_file, $admin);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.staff.import'))->with('success', 'ファイルのインポートに成功しました。');
    }

    /**
     * スタッフ一括インポート画面のインポートファイルダウンロード
     */
    public function importFile(string $importHistoryId): BinaryFileResponse
    {
        $importHistoryId = is_numeric($importHistoryId) ? $importHistoryId : null;
        if (!$importHistoryId) {
            abort(404);
        }

        /** @var ImportFileService $service */
        $service = app(ImportFileService::class);
        [$importFilePath, $importFileName] = $service->getImportFilePath((int)$importHistoryId);

        return response()->download(Storage::disk('local')->path($importFilePath), $importFileName);
    }
}

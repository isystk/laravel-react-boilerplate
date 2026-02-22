<?php

namespace App\Services\Admin\Staff\Import;

use App\Domain\Entities\Admin;
use App\Domain\Entities\ImportHistory;
use App\Domain\Repositories\ImportHistory\ImportHistoryRepositoryInterface;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use App\Enums\OperationLogType;
use App\Jobs\Import\ImportStaffJobs;
use App\Services\BaseService;
use App\Services\Common\OperationLogService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class ImportService extends BaseService
{
    public function __construct(
        private readonly ImportHistoryRepositoryInterface $importHistoryRepository,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * 管理者をインポートするJobを登録します。
     */
    public function createJob(UploadedFile $importFile, Admin $admin): void
    {
        // 処理中（または処理待ち）のJobが存在する場合は何もしない。
        $hasProcessing = $this->importHistoryRepository->hasProcessingByImportHistory(ImportType::Staff);
        if ($hasProcessing) {
            throw ValidationException::withMessages(['import_file' => '処理中のファイルがあります。しばらくお待ち下さい。']);
        }

        // アップロードファイルをストレージに保存
        $directory = 'import_job/' . ImportType::Staff->value;
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
        $filePath = $importFile->store($directory);

        if ($filePath === false) {
            throw ValidationException::withMessages(['import_file' => 'An unexpected error has occurred.']);
        }
        // インポート履歴の登録
        $importHistory = $this->importHistoryRepository->create([
            'type'           => ImportType::Staff,
            'file_name'      => $importFile->getClientOriginalName(),
            'status'         => JobStatus::Waiting, // ステータスを「処理待ち」で登録
            'import_user_id' => $admin->id,
            'import_at'      => time(),
            'save_file_name' => basename($filePath),
        ]);
        if (!$importHistory instanceof ImportHistory) {
            throw new RuntimeException('An unexpected error has occurred.');
        }
        // インポートJOBを溜める
        dispatch(new ImportStaffJobs($admin, $filePath, $importFile->getClientOriginalName(), $importHistory->id));

        $this->operationLogService->logAdminAction(
            Auth::guard('admin')->id(),
            OperationLogType::AdminStaffImport,
            "スタッフをインポート (インポート履歴 ID: {$importHistory->id})",
            request()->ip()
        );
    }
}

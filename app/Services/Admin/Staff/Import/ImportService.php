<?php

namespace App\Services\Admin\Staff\Import;

use App\Domain\Entities\Admin;
use App\Domain\Entities\ImportHistory;
use App\Domain\Repositories\ImportHistory\ImportHistoryRepository;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use App\Jobs\Import\ImportStaffJobs;
use App\Services\BaseService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use RuntimeException;


class ImportService extends BaseService
{
    private ImportHistoryRepository $importHistoryRepository;

    /**
     * Create a new controller instance.
     *
     * @param ImportHistoryRepository $importHistoryRepository
     */
    public function __construct(
        ImportHistoryRepository $importHistoryRepository
    ) {
        $this->importHistoryRepository = $importHistoryRepository;
    }


    /**
     * 管理者をインポートするJobを登録します。
     * @param UploadedFile $importFile
     * @param Admin $admin
     * @return void
     */
    public function createJob(UploadedFile $importFile, Admin $admin): void
    {
        // 処理中（または処理待ち）のJobが存在する場合は何もしない。
        $hasProcessing = $this->importHistoryRepository->hasProcessingByImportHistory(ImportType::Staff);
        if ($hasProcessing) {
            throw ValidationException::withMessages(['import_file' => '処理中のファイルがあります。しばらくお待ち下さい。']);
        }

        // アップロードファイルをストレージに保存
        $disk = 'local'; // ローカルストレージを指定
        $directory = 'import_job/' . ImportType::Staff->value;
        if (!Storage::disk($disk)->exists($directory)) {
            Storage::disk($disk)->makeDirectory($directory);
        }
        $filePath = $importFile->store($directory, $disk);

        if (false === $filePath) {
            throw ValidationException::withMessages(['import_file' => 'An unexpected error has occurred.']);
        }
        // インポート履歴の登録
        $importHistory = $this->importHistoryRepository->create([
            'type' => ImportType::Staff->value,
            'file_name' => $importFile->getClientOriginalName(),
            'status' => JobStatus::Waiting->value, // ステータスを「処理待ち」で登録
            'import_user_id' => $admin->id,
            'import_at' => time(),
            'save_file_name' => basename($filePath),
        ]);
        if (!$importHistory instanceof ImportHistory) {
            throw new RuntimeException('An unexpected error has occurred.');
        }
        // インポートJOBを溜める
        ImportStaffJobs::dispatch($admin, $filePath, $importFile->getClientOriginalName(), $importHistory->id);
    }
}

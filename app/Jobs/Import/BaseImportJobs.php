<?php

namespace App\Jobs\Import;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\ImportHistory\ImportHistoryRepository;
use App\Enums\JobStatus;
use App\Jobs\BaseJobs;
use Closure;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

/**
 * インポートJOB
 */
abstract class BaseImportJobs extends BaseJobs
{
    protected Admin $admin;

    protected string $filePath;

    protected string $fileName;

    protected int $importHistoryId;

    /**
     * Create a new job instance.
     */
    public function __construct(Admin $admin, string $filePath, string $fileName, int $importHistoryId)
    {
        $this->admin = $admin;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->importHistoryId = $importHistoryId;
    }

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        $jobName = class_basename(get_class($this));
        try {
            $this->outputLog($jobName . ' start. admin:[' . $this->admin['name'] . '] file:[' . $this->fileName . ']');

            // インポート履歴のステータスを「処理中」に更新
            $this->changeStatus(JobStatus::Processing);

            $import = $this->createImporter()(storage_path('app') . '/private/' . $this->filePath, $this->fileName);
            // 入力チェック
            $errors = $import->validate();
            if (0 < count($errors)) {
                throw new RuntimeException(implode(' ', $errors));
            }
            // DB登録処理
            DB::transaction(function () use ($import) {
                $rows = $import->getSheets()[0]; // 先頭シートのデータを取得
                $this->importData($rows);
            });

            // インポート履歴のステータスを「正常終了」に更新
            $this->changeStatus(JobStatus::Success);

            $this->outputLog($jobName . ' success. admin:[' . $this->admin['name'] . '] file:[' . $this->fileName . ']');
        } catch (Throwable $e) {
            $this->outputLog($jobName . ' error. admin:[' . $this->admin['name'] . '] file:[' . $this->fileName . '] message:[' . $e->getMessage() . ']');
            $this->outputLog($e->getTraceAsString());

            // インポート履歴のステータスを「異常終了」に更新
            $this->changeStatus(JobStatus::Failure);

            throw $e;
        }
    }

    /**
     * インポート履歴のステータスを更新します。
     */
    private function changeStatus(JobStatus $status): void
    {
        if (null === $this->job) {
            throw new RuntimeException('An unexpected error has occurred.');
        }
        $importHistoryRepository = app(ImportHistoryRepository::class);
        $importHistoryRepository->update([
            'job_id' => $this->job->getJobId(),
            'status' => $status,
        ], $this->importHistoryId);
    }

    abstract protected function createImporter(): Closure;

    /**
     * インポート処理を実行します。
     *
     * @param  array<array<string, ?string>>  $rows
     */
    abstract protected function importData(array $rows): void;
}

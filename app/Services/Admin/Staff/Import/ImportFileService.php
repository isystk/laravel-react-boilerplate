<?php

namespace App\Services\Admin\Staff\Import;

use App\Domain\Entities\ImportHistory;
use App\Domain\Repositories\ImportHistory\ImportHistoryRepository;
use App\Enums\ImportType;
use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class importFileService extends BaseService
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
     * インポートしたファイルのパスを取得します。
     * @param int $importHistoryId
     * @return array{
     *     0: string,
     *     1: string,
     * }
     */
    public function getImportFilePath(int $importHistoryId): array
    {
        /** @var ImportHistory $importHistory */
        $importHistory = $this->importHistoryRepository->findById($importHistoryId);

        $importFilePath = 'import_job/' . ImportType::Staff->value . '/' . $importHistory->save_file_name;
        if (!Storage::disk('local')->exists($importFilePath)) {
            // ファイルが存在しない場合のエラーハンドリング
            throw new NotFoundHttpException();
        }
        return [$importFilePath, $importHistory->file_name];
    }

}

<?php

namespace App\Services\Admin\Staff\Import;

use App\Domain\Entities\ImportHistory;
use App\Domain\Repositories\ImportHistory\ImportHistoryRepositoryInterface;
use App\Enums\ImportType;
use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImportFileService extends BaseService
{
    public function __construct(private readonly ImportHistoryRepositoryInterface $importHistoryRepository) {}

    /**
     * インポートしたファイルのパスを取得します。
     *
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
        if (!Storage::exists($importFilePath)) {
            // ファイルが存在しない場合のエラーハンドリング
            throw new NotFoundHttpException;
        }

        return [$importFilePath, $importHistory->file_name];
    }
}

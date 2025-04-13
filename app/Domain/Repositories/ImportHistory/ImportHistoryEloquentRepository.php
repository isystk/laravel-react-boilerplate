<?php

namespace App\Domain\Repositories\ImportHistory;

use App\Domain\Entities\ImportHistory;
use App\Domain\Repositories\BaseEloquentRepository;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use Illuminate\Support\Collection;

class ImportHistoryEloquentRepository extends BaseEloquentRepository implements ImportHistoryRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return ImportHistory::class;
    }

    /**
     * インポートタイプからデータを取得します。
     * @param ImportType $importType
     * @return Collection<int, ImportHistory>
     */
    public function getByImportHistory(ImportType $importType): Collection {
        /** @var Collection<int, ImportHistory> */
        return $this->model
            ->where('type', $importType->value)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 処理中（または処理待ち）のデータが存在する場合はTrueを返却します。
     * @param ImportType $importType
     * @return bool
     */
    public function hasProcessingByImportHistory(ImportType $importType): bool {
        return $this->model
            ->where('type', $importType->value)
            ->whereIn('status', [
                JobStatus::Waiting->value,
                JobStatus::Processing->value
            ])
            ->exists();
    }

}

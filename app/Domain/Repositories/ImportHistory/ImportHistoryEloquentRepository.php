<?php

namespace App\Domain\Repositories\ImportHistory;

use App\Domain\Entities\ImportHistory;
use App\Domain\Repositories\BaseEloquentRepository;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use Illuminate\Support\Collection;

class ImportHistoryEloquentRepository extends BaseEloquentRepository implements ImportHistoryRepository
{
    protected function model(): string
    {
        return ImportHistory::class;
    }

    /**
     * インポートタイプからデータを取得します。
     *
     * @return Collection<int, ImportHistory>
     */
    public function getByImportHistory(ImportType $importType): Collection
    {
        /** @var Collection<int, ImportHistory> */
        return $this->model
            ->where('type', $importType)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * 処理中（または処理待ち）のデータが存在する場合はTrueを返却します。
     */
    public function hasProcessingByImportHistory(ImportType $importType): bool
    {
        return $this->model
            ->where('type', $importType)
            ->whereIn('status', [
                JobStatus::Waiting,
                JobStatus::Processing,
            ])
            ->exists();
    }
}

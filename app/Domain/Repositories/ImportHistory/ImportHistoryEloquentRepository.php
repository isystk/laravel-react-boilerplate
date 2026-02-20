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
     * {@inheritDoc}
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
     * {@inheritDoc}
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

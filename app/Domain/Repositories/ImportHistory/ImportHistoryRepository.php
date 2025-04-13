<?php

namespace App\Domain\Repositories\ImportHistory;

use App\Domain\Entities\ImportHistory;
use App\Domain\Repositories\BaseRepository;
use App\Enums\ImportType;
use Illuminate\Support\Collection;

interface ImportHistoryRepository extends BaseRepository
{
    /**
     * インポートタイプからデータを取得します。
     * @param ImportType $importType
     * @return Collection<int, ImportHistory>
     */
    public function getByImportHistory(ImportType $importType): Collection;

    /**
     * 処理中（または処理待ち）のデータが存在する場合はTrueを返却します。
     * @param ImportType $importType
     * @return bool
     */
    public function hasProcessingByImportHistory(ImportType $importType): bool;

}

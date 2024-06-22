<?php

namespace App\Domain\Repositories\ImportHistory;

use App\Domain\Entities\ImportHistory;
use App\Domain\Repositories\BaseEloquentRepository;

class ImportHistoryEloquentRepository extends BaseEloquentRepository implements ImportHistoryRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return ImportHistory::class;
    }

}

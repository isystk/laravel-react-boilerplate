<?php

namespace App\Jobs\Import;

use App\FileIO\Imports\BaseImport;
use App\FileIO\Imports\StaffImport;
use App\Services\Jobs\Import\StaffRegistService;
use Closure;

class ImportStaffJobs extends BaseImportJobs
{

    /**
     * Importクラスのインスタンスを返却する
     * @return Closure
     */
    protected function createImporter(): Closure
    {
        return static function (string $filePath) {
            return new StaffImport($filePath);
        };
    }

    /**
     * スタッフを一括登録する
     * @param BaseImport $import
     */
    protected function importData(BaseImport $import): void
    {
        app(StaffRegistService::class)->exec(
            $this->importHistoryId,
            $import,
            $this->fileName,
            $this->admin->id,
            function ($message)
            {
                $this->outputLog($message);
            }
        );
    }
}

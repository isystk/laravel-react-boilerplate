<?php

namespace App\Jobs\Import;

use App\FileIO\Imports\StaffImport;
use App\Services\Jobs\Import\StaffRegistService;
use Closure;

class ImportStaffJobs extends BaseImportJobs
{
    /**
     * Importクラスのインスタンスを返却する
     */
    protected function createImporter(): Closure
    {
        return static function (string $filePath) {
            return new StaffImport($filePath);
        };
    }

    /**
     * スタッフを一括登録する
     *
     * @param  array<array<string, ?string>>  $rows
     */
    protected function importData(array $rows): void
    {
        app(StaffRegistService::class)->exec(
            $rows,
            function ($message) {
                $this->outputLog($message);
            }
        );
    }
}

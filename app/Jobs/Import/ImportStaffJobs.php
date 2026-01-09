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
        return static fn (string $filePath) => new StaffImport($filePath);
    }

    /**
     * スタッフを一括登録する
     *
     * @param array<array<string, ?string>> $rows
     */
    protected function importData(array $rows): void
    {
        $service = app(StaffRegistService::class);
        $service->exec(
            $rows,
            function ($message) {
                $this->outputLog($message);
            }
        );
    }
}

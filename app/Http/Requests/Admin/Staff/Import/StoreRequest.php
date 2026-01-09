<?php

namespace App\Http\Requests\Admin\Staff\Import;

use App\FileIO\Imports\StaffImport;
use App\Http\Requests\Admin\BaseImportRequest;

class StoreRequest extends BaseImportRequest
{
    protected function createImporter(): \Closure
    {
        return static fn (string $filePath) => new StaffImport($filePath);
    }
}

<?php

namespace App\Http\Requests\Admin\Staff\Import;

use App\FileIO\Imports\StaffImport;
use App\Http\Requests\Admin\BaseImportRequest;

class StoreRequest extends BaseImportRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('high-manager');
    }

    /**
     * @return \Closure
     */
    protected function createImporter(): \Closure
    {
        return static function (string $filePath)
        {
            return new StaffImport($filePath);
        };
    }
}

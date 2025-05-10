<?php

namespace App\Services\Admin\Photo;

use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;

class DestroyService extends BaseService
{

    /**
     * 写真を削除します。
     */
    public function delete(string $fileName): void
    {
        Storage::delete($fileName);
    }
}

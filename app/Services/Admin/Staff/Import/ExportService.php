<?php

namespace App\Services\Admin\Staff\Import;

use App\Domain\Repositories\Admin\AdminRepositoryInterface;
use App\FileIO\Exports\StaffExport;
use App\Services\BaseService;

class ExportService extends BaseService
{
    public function __construct(private readonly AdminRepositoryInterface $adminRepository) {}

    /**
     * エクスポート用のオブジェクトを取得します。
     */
    public function getExport(): StaffExport
    {
        $admins = $this->adminRepository->getAllOrderById();

        return new StaffExport($admins);
    }
}

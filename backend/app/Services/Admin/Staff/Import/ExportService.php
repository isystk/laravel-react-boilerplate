<?php

namespace App\Services\Admin\Staff\Import;

use App\Domain\Repositories\Admin\AdminRepository;
use App\FileIO\Exports\StaffExport;
use App\Services\BaseService;

class ExportService extends BaseService
{
    private AdminRepository $adminRepository;

    /**
     * Create a new controller instance.
     *
     * @param AdminRepository $adminRepository
     */
    public function __construct(
        AdminRepository $adminRepository
    )
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * エクスポート用のオブジェクトを取得します。
     * @return StaffExport
     */
    public function getExport(): StaffExport
    {
        $admins = $this->adminRepository->getAllOrderById();
        return new StaffExport($admins);
    }

}

<?php

namespace App\Services\Admin\Staff\Import;

use App\Domain\Repositories\Admin\AdminRepository;
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
     * 管理者をエクスポートします。
     * @return array
     */
    public function export(): array
    {

        return [];
    }
}

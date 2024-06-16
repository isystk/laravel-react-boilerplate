<?php

namespace App\Services\Admin\Staff\Import;

use App\Domain\Repositories\Admin\AdminRepository;
use App\Services\BaseService;

class RegistService extends BaseService
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
     * 管理者をインポートするJobを登録します。
     * @return array
     */
    public function import(): array
    {

        return [];
    }
}

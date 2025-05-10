<?php

namespace App\Services\Admin\Staff;

use App\Domain\Repositories\Admin\AdminRepository;
use App\Services\BaseService;

class DestroyService extends BaseService
{
    private AdminRepository $adminRepository;

    public function __construct(
        AdminRepository $adminRepository
    ) {
        $this->adminRepository = $adminRepository;
    }

    /**
     * 管理者を削除します。
     */
    public function delete(int $id): void
    {
        // 管理者テーブルを削除
        $this->adminRepository->delete($id);
    }
}

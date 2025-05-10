<?php

namespace App\Services\Admin;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Services\BaseService;

class PasswordChangeUpdateService extends BaseService
{
    private AdminRepository $adminRepository;

    public function __construct(
        AdminRepository $adminRepository
    ) {
        $this->adminRepository = $adminRepository;
    }

    /**
     * パスワードを変更します。
     */
    public function update(int $adminId, string $newPassword): Admin
    {
        return $this->adminRepository->update(
            $adminId,
            [
                'password' => $newPassword,
            ]
        );
    }

}

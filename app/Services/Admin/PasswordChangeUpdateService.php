<?php

namespace App\Services\Admin;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Services\BaseService;

class PasswordChangeUpdateService extends BaseService
{
    private AdminRepository $adminRepository;

    /**
     * Create a new controller instance.
     *
     * @param AdminRepository $adminRepository
     */
    public function __construct(
        AdminRepository $adminRepository
    ) {
        $this->adminRepository = $adminRepository;
    }

    /**
     * パスワードを変更します。
     * @param int $adminId
     * @param string $newPassword
     * @return Admin
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

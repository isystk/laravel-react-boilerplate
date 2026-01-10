<?php

namespace App\Services\Admin;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Services\BaseService;

class PasswordChangeUpdateService extends BaseService
{
    public function __construct(private readonly AdminRepository $adminRepository) {}

    /**
     * パスワードを変更します。
     */
    public function update(int $adminId, string $newPassword): Admin
    {
        return $this->adminRepository->update([
            'password' => $newPassword,
        ], $adminId);
    }
}

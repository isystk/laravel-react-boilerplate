<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepositoryInterface;
use App\Dto\Request\Admin\Staff\UpdateDto;
use App\Services\BaseService;

class UpdateService extends BaseService
{
    public function __construct(private readonly AdminRepositoryInterface $adminRepository) {}

    /**
     * 管理者を更新します。
     */
    public function update(int $adminId, UpdateDto $dto): Admin
    {
        return $this->adminRepository->update([
            'name'  => $dto->name,
            'email' => $dto->email,
            'role'  => $dto->role,
        ], $adminId);
    }
}

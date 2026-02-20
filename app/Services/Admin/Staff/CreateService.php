<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepositoryInterface;
use App\Dto\Request\Admin\Staff\CreateDto;
use App\Services\BaseService;

class CreateService extends BaseService
{
    public function __construct(private readonly AdminRepositoryInterface $adminRepository) {}

    /**
     * 管理者を登録します。
     */
    public function save(CreateDto $dto): Admin
    {
        return $this->adminRepository->create([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'password' => $dto->password,
            'role'     => $dto->role,
        ]);
    }
}

<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Http\Requests\Admin\Staff\UpdateRequest;
use App\Services\BaseService;

class UpdateService extends BaseService
{
    private AdminRepository $adminRepository;

    public function __construct(
        AdminRepository $adminRepository
    ) {
        $this->adminRepository = $adminRepository;
    }

    /**
     * 管理者を更新します。
     */
    public function update(int $adminId, UpdateRequest $request): Admin
    {
        $model = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        return $this->adminRepository->update(
            $adminId,
            $model
        );
    }
}

<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Http\Requests\Admin\Staff\UpdateRequest;
use App\Services\BaseService;

class UpdateService extends BaseService
{
    public function __construct(private readonly AdminRepository $adminRepository) {}

    /**
     * 管理者を更新します。
     */
    public function update(int $adminId, UpdateRequest $request): Admin
    {
        return $this->adminRepository->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ], $adminId);
    }
}

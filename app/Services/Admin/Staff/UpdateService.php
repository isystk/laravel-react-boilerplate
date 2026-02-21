<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepositoryInterface;
use App\Dto\Request\Admin\Staff\UpdateDto;
use App\Enums\OperationLogType;
use App\Services\BaseService;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Auth;

class UpdateService extends BaseService
{
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * 管理者を更新します。
     */
    public function update(int $adminId, UpdateDto $dto): Admin
    {
        $admin = $this->adminRepository->update([
            'name'  => $dto->name,
            'email' => $dto->email,
            'role'  => $dto->role,
        ], $adminId);

        $this->operationLogService->logAdminAction(
            Auth::guard('admin')->id(),
            OperationLogType::AdminStaffUpdate,
            "スタッフ情報を更新 (スタッフID: {$adminId})",
            request()->ip()
        );

        return $admin;
    }
}

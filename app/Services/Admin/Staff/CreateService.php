<?php

namespace App\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepositoryInterface;
use App\Dto\Request\Admin\Staff\CreateDto;
use App\Enums\OperationLogType;
use App\Services\BaseService;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Auth;

class CreateService extends BaseService
{
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * 管理者を登録します。
     */
    public function save(CreateDto $dto): Admin
    {
        $admin = $this->adminRepository->create([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'password' => $dto->password,
            'role'     => $dto->role,
        ]);

        $this->operationLogService->logAdminAction(
            Auth::guard('admin')->id(),
            OperationLogType::AdminStaffCreate,
            "スタッフを作成 (スタッフID: {$admin->id}, 名前: {$admin->name})",
            request()->ip()
        );

        return $admin;
    }
}

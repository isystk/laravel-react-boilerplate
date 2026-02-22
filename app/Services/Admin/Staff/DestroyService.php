<?php

namespace App\Services\Admin\Staff;

use App\Domain\Repositories\Admin\AdminRepositoryInterface;
use App\Enums\OperationLogType;
use App\Services\BaseService;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Auth;

class DestroyService extends BaseService
{
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * 管理者を削除します。
     */
    public function delete(int $id): void
    {
        // 管理者テーブルを削除
        $this->adminRepository->delete($id);

        $this->operationLogService->logAdminAction(
            Auth::guard('admin')->id(),
            OperationLogType::AdminStaffDelete,
            "スタッフを削除 (スタッフID: {$id})",
            request()->ip()
        );
    }
}

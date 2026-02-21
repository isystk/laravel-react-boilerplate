<?php

namespace App\Services\Admin\User;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Enums\OperationLogType;
use App\Enums\UserStatus;
use App\Services\BaseService;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Auth;

class SuspendService extends BaseService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * ユーザーをアカウント停止にします。
     */
    public function suspend(int $id): void
    {
        $this->userRepository->update([
            'status' => UserStatus::Suspended,
        ], $id);

        $this->operationLogService->logAdminAction(
            Auth::guard('admin')->id(),
            OperationLogType::AdminUserSuspend,
            "ユーザーアカウントを停止 (ユーザーID: {$id})",
            request()->ip()
        );
    }

    /**
     * ユーザーを有効にします。
     */
    public function activate(int $id): void
    {
        $this->userRepository->update([
            'status' => UserStatus::Active,
        ], $id);

        $this->operationLogService->logAdminAction(
            Auth::guard('admin')->id(),
            OperationLogType::AdminUserActivate,
            "ユーザーアカウントを有効化 (ユーザーID: {$id})",
            request()->ip()
        );
    }
}

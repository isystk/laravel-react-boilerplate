<?php

namespace App\Listeners;

use App\Domain\Entities\User;
use App\Enums\OperationLogType;
use App\Services\Common\OperationLogService;
use Illuminate\Auth\Events\Logout;

class LogUserLogoutListener
{
    public function __construct(private readonly OperationLogService $operationLogService) {}

    /**
     * ユーザーログアウト時に操作ログを記録します。
     */
    public function handle(Logout $event): void
    {
        // フロントユーザーのログアウトのみ記録する
        if ($event->guard !== 'web') {
            return;
        }

        /** @var ?User $user */
        $user = $event->user;

        if ($event->user === null) {
            return;
        }

        $this->operationLogService->logUserAction(
            $user->id,
            OperationLogType::UserLogout,
            'ログアウト',
            request()->ip()
        );
    }
}

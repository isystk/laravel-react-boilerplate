<?php

namespace App\Services\Admin\Contact;

use App\Domain\Repositories\Contact\ContactRepositoryInterface;
use App\Enums\OperationLogType;
use App\Services\BaseService;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Auth;

class DestroyService extends BaseService
{
    public function __construct(
        private readonly ContactRepositoryInterface $contactRepository,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * お問い合わせを削除します。
     */
    public function delete(int $contactId): void
    {
        $this->contactRepository->delete($contactId);

        $this->operationLogService->logAdminAction(
            Auth::guard('admin')->id(),
            OperationLogType::AdminContactDelete,
            "お問い合わせを削除 (お問い合わせID: {$contactId})",
            request()->ip()
        );
    }
}

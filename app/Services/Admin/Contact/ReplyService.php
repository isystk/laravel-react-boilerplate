<?php

namespace App\Services\Admin\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\ContactReply\ContactReplyRepositoryInterface;
use App\Enums\OperationLogType;
use App\Mails\ContactReplyToUser;
use App\Services\BaseService;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Mail;

class ReplyService extends BaseService
{
    public function __construct(
        private readonly ContactReplyRepositoryInterface $contactReplyRepository,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * お問い合わせへの返信を保存し、ユーザーにメールを送信します。
     */
    public function reply(Contact $contact, int $adminId, string $body): void
    {
        $this->contactReplyRepository->create([
            'contact_id' => $contact->id,
            'admin_id'   => $adminId,
            'body'       => $body,
        ]);

        Mail::to($contact->user->email)->send(new ContactReplyToUser($contact, $body));

        $this->operationLogService->logAdminAction(
            $adminId,
            OperationLogType::AdminContactReply,
            "お問い合わせに返信 (お問い合わせID: {$contact->id})",
            request()->ip()
        );
    }
}

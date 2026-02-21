<?php

namespace App\Services\Admin\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\ContactReply\ContactReplyRepository;
use App\Mails\ContactReplyToUser;
use App\Services\BaseService;
use Illuminate\Support\Facades\Mail;

class ReplyService extends BaseService
{
    public function __construct(private readonly ContactReplyRepository $contactReplyRepository) {}

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
    }
}

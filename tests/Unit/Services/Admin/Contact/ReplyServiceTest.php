<?php

namespace Tests\Unit\Services\Admin\Contact;

use App\Mails\ContactReplyToUser;
use App\Services\Admin\Contact\ReplyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\BaseTest;

class ReplyServiceTest extends BaseTest
{
    use RefreshDatabase;

    private ReplyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ReplyService::class);
    }

    public function test_reply_DBに返信が保存される事(): void
    {
        Mail::fake();

        $admin   = $this->createDefaultAdmin();
        $contact = $this->createDefaultContact();

        $this->service->reply($contact, $admin->id, '返信内容です。');

        $this->assertDatabaseHas('contact_replies', [
            'contact_id' => $contact->id,
            'admin_id'   => $admin->id,
            'body'       => '返信内容です。',
        ]);
    }

    public function test_reply_ユーザーにメールが送信される事(): void
    {
        Mail::fake();

        $user    = $this->createDefaultUser(['email' => 'user@example.com']);
        $admin   = $this->createDefaultAdmin();
        $contact = $this->createDefaultContact(['user_id' => $user->id]);

        $this->service->reply($contact, $admin->id, '返信内容です。');

        Mail::assertSent(ContactReplyToUser::class, static fn ($mail) => $mail->hasTo($user->email));
    }

    public function test_reply_メールは1通のみ送信される事(): void
    {
        Mail::fake();

        $admin   = $this->createDefaultAdmin();
        $contact = $this->createDefaultContact();

        $this->service->reply($contact, $admin->id, '返信内容です。');

        Mail::assertSentCount(1);
    }
}

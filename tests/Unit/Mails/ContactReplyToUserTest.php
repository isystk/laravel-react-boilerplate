<?php

namespace Tests\Unit\Mails;

use App\Mails\ContactReplyToUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\BaseTest;

class ContactReplyToUserTest extends BaseTest
{
    use RefreshDatabase;

    public function test_notification(): void
    {
        Mail::fake();

        $user = $this->createDefaultUser([
            'name'  => 'テストユーザー',
            'email' => 'test@example.com',
        ]);
        $contact   = $this->createDefaultContact(['user_id' => $user->id]);
        $replyBody = 'お問い合わせへの返信内容です。';

        Mail::to($user->email)->send(new ContactReplyToUser($contact, $replyBody));

        Mail::assertSent(ContactReplyToUser::class, static function ($mail) use ($user, $replyBody) {
            $mail->build();

            return $mail->hasTo($user->email)
                && $mail->textView === 'mails.contact_reply_to_user_text'
                && $mail->subject === config('const.mail.subject.contact_reply_to_user')
                && $mail->from[0]['address'] === config('mail.from.address')
                && $mail->viewData['replyBody'] === $replyBody;
        });
    }
}

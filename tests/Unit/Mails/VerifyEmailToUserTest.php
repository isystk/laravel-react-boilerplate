<?php

namespace Mails;

use App\Mails\VerifyEmailToUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class VerifyEmailToUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification(): void
    {
        Notification::fake();

        $user = $this->createDefaultUser([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);
        $user->notify(new VerifyEmailToUser($user));

        Notification::assertSentTo(
            $user,
            VerifyEmailToUser::class,
            static function ($notification, $channels) use ($user) {
                $mail = $notification->toMail($user);

                return 'mails.verify_email_to_user_html' === $mail->view['html']
                    && 'mails.verify_email_to_user_text' === $mail->view['text']
                    && $mail->subject === config('const.mail.subject.verify_email_to_user')
                    && $mail->from[0] === config('mail.from.address');
            }
        );
    }
}

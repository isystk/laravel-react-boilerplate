<?php

namespace Mails;

use App\Mails\ResetPasswordToUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\BaseTest;

class ResetPasswordToUserTest extends BaseTest
{
    use RefreshDatabase;

    public function test_notification(): void
    {
        Notification::fake();

        $user = $this->createDefaultUser([
            'name'  => 'テストユーザー',
            'email' => 'test@example.com',
        ]);
        $token = 'token';
        $user->notify(new ResetPasswordToUser($user, $token));

        Notification::assertSentTo(
            $user,
            ResetPasswordToUser::class,
            static function ($notification, $channels) use ($user) {
                $mail = $notification->toMail($user);

                return $mail->view['html'] === 'mails.reset_password_to_user_html'
                    && $mail->view['text'] === 'mails.reset_password_to_user_text'
                    && $mail->subject === config('const.mail.subject.reset_password_to_user')
                    && $mail->from[0] === config('mail.from.address');
            }
        );
    }
}

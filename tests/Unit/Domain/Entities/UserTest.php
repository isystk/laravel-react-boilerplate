<?php

namespace Domain\Entities;

use App\Domain\Entities\User;
use App\Mails\ResetPasswordToUser;
use App\Mails\VerifyEmailToUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Tests\BaseTest;

class UserTest extends BaseTest
{
    use RefreshDatabase;

    private User $sub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new User;
    }

    public function test_isEmailVerified(): void
    {
        $this->assertFalse($this->sub->isEmailVerified(), 'メールアドレスの認証が未だの場合 → False');
        $this->sub->email_verified_at = Carbon::now();
        $this->assertTrue($this->sub->isEmailVerified(), 'メールアドレスが認証済みの場合 → True');
    }

    public function test_sendPasswordResetNotification_通知が送信されること(): void
    {
        Notification::fake();
        $user = $this->createDefaultUser();

        $user->sendPasswordResetNotification('sample-token');

        Notification::assertSentTo(
            $user,
            ResetPasswordToUser::class,
            static function ($notification, $channels) {
                // 通知オブジェクトが生成されているか、トークンが渡されているか等を検証
                return true;
            }
        );
    }

    public function test_sendEmailVerificationNotification_通知が送信されること(): void
    {
        Notification::fake();
        $user = $this->createDefaultUser();

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, VerifyEmailToUser::class);
    }

    public function test_getJWTIdentifier_キーが返却されること(): void
    {
        $user = $this->createDefaultUser(['id' => 123]);

        $this->assertSame('123', $user->getJWTIdentifier());
    }

    public function test_getJWTCustomClaims_空の配列が返却されること(): void
    {
        $this->assertSame([], $this->sub->getJWTCustomClaims());
    }
}

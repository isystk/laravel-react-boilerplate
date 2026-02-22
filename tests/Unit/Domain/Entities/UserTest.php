<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\User;
use App\Enums\ImageType;
use App\Enums\UserStatus;
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

    public function test_正しくキャストされる事(): void
    {
        $model = $this->createDefaultUser([
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertInstanceOf(UserStatus::class, $model->status);
        $this->assertInstanceOf(Carbon::class, $model->created_at);
        $this->assertInstanceOf(Carbon::class, $model->updated_at);
        $this->assertInstanceOf(Carbon::class, $model->deleted_at);
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

            // 通知オブジェクトが生成されているか、トークンが渡されているか等を検証

            static fn ($notification, $channels) => true
        );
    }

    public function test_sendEmailVerificationNotification_通知が送信されること(): void
    {
        Notification::fake();
        $user = $this->createDefaultUser();

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, VerifyEmailToUser::class);
    }

    public function test_avatarImage_リレーションが正しく動作すること(): void
    {
        $image = $this->createDefaultImage([
            'file_name' => 'avatar.png',
            'type'      => ImageType::User->value,
        ]);

        $user = $this->createDefaultUser(['avatar_image_id' => $image->id]);

        $this->assertNotNull($user->avatarImage);
        $this->assertSame($image->id, $user->avatarImage->id);
        $this->assertSame('avatar.png', $user->avatarImage->file_name);
    }

    public function test_avatarImage_画像が未設定の場合nullが返却されること(): void
    {
        $user = $this->createDefaultUser(['avatar_image_id' => null]);

        $this->assertNull($user->avatarImage);
    }
}

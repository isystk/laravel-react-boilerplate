<?php

namespace Tests\Unit\Services\Front\Auth\GoogleLogin;

use App\Domain\Entities\User;
use App\Services\Front\Auth\GoogleLogin\HandleGoogleCallbackService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\BaseTest;

class HandleGoogleCallbackServiceTest extends BaseTest
{
    use RefreshDatabase;

    private HandleGoogleCallbackService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(HandleGoogleCallbackService::class);
    }

    public function test_findOrCreate_新規ユーザーが作成されること(): void
    {
        $googleUser         = new SocialiteUser;
        $googleUser->id     = 'google-new-123';
        $googleUser->name   = 'New Google User';
        $googleUser->email  = 'newgoogle@test.com';
        $googleUser->avatar = 'https://lh3.googleusercontent.com/avatar.png';

        $user = $this->service->findOrCreate($googleUser);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('New Google User', $user->name);
        $this->assertSame('newgoogle@test.com', $user->email);
        $this->assertSame('https://lh3.googleusercontent.com/avatar.png', $user->avatar_url);
        $this->assertSame('google-new-123', $user->google_id);
        $this->assertNotNull($user->email_verified_at);

        $this->assertDatabaseHas('users', [
            'google_id' => 'google-new-123',
            'email'     => 'newgoogle@test.com',
        ]);
    }

    public function test_findOrCreate_既存ユーザーが返却されること(): void
    {
        $existingUser = $this->createDefaultUser([
            'name'       => 'Existing User',
            'email'      => 'existing@test.com',
            'google_id'  => 'google-existing-456',
            'avatar_url' => 'https://lh3.googleusercontent.com/existing.png',
        ]);

        $googleUser         = new SocialiteUser;
        $googleUser->id     = 'google-existing-456';
        $googleUser->name   = 'Updated Name';
        $googleUser->email  = 'updated@test.com';
        $googleUser->avatar = 'https://lh3.googleusercontent.com/updated.png';

        $user = $this->service->findOrCreate($googleUser);

        $this->assertSame($existingUser->id, $user->id);
        $this->assertSame('Existing User', $user->name, '既存ユーザーの名前は更新されない');
        $this->assertSame(1, User::where('google_id', 'google-existing-456')->count(), '重複ユーザーが作成されない');
    }
}

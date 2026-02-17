<?php

namespace Tests\Feature\Http\Controllers\Front\Auth;

use App\Domain\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\BaseTest;

class GoogleLoginControllerTest extends BaseTest
{
    use RefreshDatabase;

    public function test_handleGoogleCallback_リダイレクト(): void
    {
        $this->get('/auth/google')
            ->assertRedirect();
    }

    public function test_handleGoogleCallback_新規ユーザー作成(): void
    {
        $this->mockSocialiteUser('google-123', 'Google User', 'google@test.com', 'https://avatar.url');

        $this->get('/auth/google/callback')
            ->assertRedirect('/home');

        $user = User::where('google_id', 'google-123')->first();
        $this->assertNotNull($user);
        $this->assertSame('Google User', $user->name);
        $this->assertSame('google@test.com', $user->email);
        $this->assertSame('https://avatar.url', $user->avatar_url);
        $this->assertNotNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);
    }

    public function test_handleGoogleCallback_例外発生時はログイン画面へリダイレクト(): void
    {
        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('user')
            ->andThrow(new \Exception('Google auth failed'));

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        $this->get('/auth/google/callback')
            ->assertRedirect('/login');
    }

    private function mockSocialiteUser(string $id, string $name, string $email, string $avatar): void
    {
        $socialiteUser         = new SocialiteUser;
        $socialiteUser->id     = $id;
        $socialiteUser->name   = $name;
        $socialiteUser->email  = $email;
        $socialiteUser->avatar = $avatar;

        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('user')
            ->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);
    }
}

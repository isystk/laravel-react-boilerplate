<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\BaseTest;

class LogoutTest extends BaseTest
{
    use RefreshDatabase;

    /**
     * api/* は EnsureFrontendRequestsAreStateful が Referer/Origin ヘッダーで
     * フロントエンドからのリクエストか判定し、その場合のみセッション認証を有効化する
     * （実ブラウザは常にRefererを送るため通常は問題にならない）。
     * テストクライアントはデフォルトでRefererを送らないため、実ブラウザ相当の条件を再現する。
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeader('referer', config('app.url'));
    }

    public function test_logout_success(): void
    {
        $user = $this->createDefaultUser();
        Auth::login($user);

        $this->assertAuthenticatedAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);

        $this->assertGuest();
    }

    public function test_logout_as_guest(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);

        $this->assertGuest();
    }
}

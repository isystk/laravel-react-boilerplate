<?php

namespace Http\Controllers\Admin;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\BaseTest;

class LoginControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_index_未ログイン時はログイン画面を表示すること(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.login');
    }

    public function test_index_ログイン済みの場合はホーム画面にリダイレクトすること(): void
    {
        $admin = $this->createDefaultAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.login'));

        $response->assertRedirect(route('admin.home'));
    }

    public function test_login_認証成功時にホームへリダイレクトすること(): void
    {
        $password = 'secret-pass';
        $admin    = $this->createDefaultAdmin([
            'email'    => 'success@test.com',
            'password' => Hash::make($password),
        ]);

        $response = $this->post(route('admin.login'), [
            'email'    => 'success@test.com',
            'password' => $password,
        ]);

        $response->assertRedirect(route('admin.home'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_login_認証失敗時にエラーと共に戻ること(): void
    {
        $this->createDefaultAdmin([
            'email'    => 'fail@test.com',
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->from(route('admin.login'))
            ->post(route('admin.login'), [
                'email'    => 'fail@test.com',
                'password' => 'wrong-password',
            ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors(['password' => '認証に失敗しました']);
        $this->assertGuest('admin');
    }

    public function test_logout_ログアウト後にセッションがクリアされログイン画面へ戻ること(): void
    {
        $admin = $this->createDefaultAdmin();

        // ログイン状態でセッションに古いIDがあることを確認
        $oldSessionId = session()->getId();

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');

        // セッションIDが新しくなっている
        $this->assertNotEquals($oldSessionId, session()->getId());
    }
}

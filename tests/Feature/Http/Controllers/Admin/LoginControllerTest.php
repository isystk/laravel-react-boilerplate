<?php

namespace Feature\Http\Controllers\Admin;

use App\Domain\Entities\Admin;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * 管理者ログインのテスト
     */
    public function testLogin(): void
    {
        Admin::factory()->create([
            'name' => '管理者A',
            'email' => 'aaa@test.com',
            'password' => Hash::make('password'),
        ]);

        $forms = [
            'email' => 'aaa@test.com',
            'password' => 'password',
        ];
        $redirectResponse = $this->post(route('admin.login'), $forms);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSee('ようこそ！管理者A');
    }
}

<?php

namespace Http\Controllers\Admin;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * 管理者ログインのテスト
     */
    public function test_login(): void
    {
        $this->createDefaultAdmin([
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

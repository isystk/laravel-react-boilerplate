<?php

namespace Http\Controllers\Front\Auth;

use App\Domain\Entities\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Tests\TestCase;

class CreateNewUserTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        ValidateCsrfToken::except(['api/*']);
    }

    /**
     * ユーザー登録のテスト
     */
    public function testCreate(): void
    {
        $items = [
            '_token' => csrf_token(),
            'name' => 'user1',
            'email' => 'user1@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        $this->post('/register', $items)
            ->assertStatus(302);

        $user = User::where(['email' => $items['email']])->first();
        $this->assertSame($items['name'], $user->name, 'ユーザーが新規登録できることをテスト');
    }
}

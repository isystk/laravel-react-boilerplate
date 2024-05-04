<?php

namespace Http\Controllers\Front\Auth;

use App\Domain\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    }

    /**
     * ユーザー登録のテスト
     */
    public function testCreate(): void
    {
        $items = [
            'name' => 'user1',
            'email' => 'user1@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        $this->withHeaders(['X-CSRF-TOKEN' => csrf_token()])
            ->post('/register', $items)
            ->assertStatus(302);

        $user = User::where(['email' => $items['email']])->first();
        $this->assertSame($items['name'], $user->name, 'ユーザーが新規登録できることをテスト');
    }
}
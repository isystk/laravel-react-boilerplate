<?php

namespace Http\Controllers\Front\Auth;

use App\Domain\Entities\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class CreateNewUserTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_create(): void
    {
        $items = [
            'name' => 'user1',
            'email' => 'user1@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $this->post('/register', $items)
            ->assertStatus(302);

        $user = User::where(['email' => $items['email']])->first();
        $this->assertSame($items['name'], $user->name, 'ユーザーが新規登録できることをテスト');
    }
}

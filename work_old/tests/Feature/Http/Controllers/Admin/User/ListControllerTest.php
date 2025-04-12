<?php

namespace Feature\Http\Controllers\Admin\User;

use App\Domain\Entities\Admin;
use App\Domain\Entities\User;
use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class ListControllerTest extends TestCase
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
     * ユーザー一覧画面表示のテスト
     */
    public function testIndex(): void
    {
        /** @var Admin $admin */
        $admin = Admin::factory([
            'name' => 'admin1',
            'email' => 'admin1@test.com',
            'role' => AdminRole::Manager->value
        ])->create();
        $this->actingAs($admin, 'admin');

        User::factory([
            'name' => 'user1',
        ])->create();

        User::factory([
            'name' => 'user2',
        ])->create();

        $response = $this->get(route('admin.user'));
        $response->assertSuccessful();
        $response->assertSeeInOrder(['user1', 'user2']);
    }

}

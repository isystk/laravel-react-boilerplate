<?php

namespace Http\Controllers\Admin\User;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListControllerTest extends TestCase
{

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
        $admin = $this->createDefaultAdmin([
            'name' => 'admin1',
            'email' => 'admin1@test.com',
            'role' => AdminRole::Manager->value,
        ]);
        $this->actingAs($admin, 'admin');

        $this->createDefaultUser([
            'name' => 'user1',
        ]);

        $this->createDefaultUser([
            'name' => 'user2',
        ]);

        $response = $this->get(route('admin.user'));
        $response->assertSuccessful();
        $response->assertSeeInOrder(['user1', 'user2']);
    }

}

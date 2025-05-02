<?php

namespace Http\Controllers\Admin\Staff;

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
     * スタッフ一覧画面表示のテスト
     */
    public function testIndex(): void
    {
        $this->createDefaultAdmin([
            'name' => 'user1',
            'email' => 'user1@test.com',
            'role' => AdminRole::HighManager->value,
        ]);
        $admin2 = $this->createDefaultAdmin([
            'name' => 'user2',
            'email' => 'user2@test.com',
            'role' => AdminRole::Manager->value,
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.staff'));
        $response->assertSuccessful();
        $response->assertSeeInOrder(['user1', 'user2']);
    }

}

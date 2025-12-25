<?php

namespace Http\Controllers\Admin\ContactForm;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ListControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * お問い合わせ一覧画面表示のテスト
     */
    public function test_index(): void
    {
        $admin = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager->value,
        ]);
        $this->actingAs($admin, 'admin');

        $this->createDefaultContactForm(['user_name' => 'user1', 'title' => 'title1']);
        $this->createDefaultContactForm(['user_name' => 'user2', 'title' => 'title2']);

        $response = $this->get(route('admin.contact'));
        $response->assertSuccessful();
        $response->assertSeeInOrder(['title2', 'title1']);
    }
}

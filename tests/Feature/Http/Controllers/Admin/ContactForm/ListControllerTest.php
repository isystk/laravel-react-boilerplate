<?php

namespace Tests\Feature\Http\Controllers\Admin\ContactForm;

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

    public function test_index_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::HighManager, 'status' => 200],
            ['role' => AdminRole::Manager,     'status' => 200],
        ];

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.contact'))
                ->assertStatus($case['status']);
        }
    }
}

<?php

namespace Tests\Feature\Http\Controllers\Admin\Staff;

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
        $this->createDefaultAdmin([
            'name'  => 'user1',
            'email' => 'user1@test.com',
            'role'  => AdminRole::HighManager->value,
        ]);
        $admin2 = $this->createDefaultAdmin([
            'name'  => 'user2',
            'email' => 'user2@test.com',
            'role'  => AdminRole::Manager->value,
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.staff'));
        $response->assertSuccessful();
        $response->assertSeeInOrder(['user2', 'user1']);
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
                ->get(route('admin.staff'))
                ->assertStatus($case['status']);
        }
    }

    public function test_guest_cannot_access(): void
    {
        $this->get(route('admin.staff'))
            ->assertRedirect(route('login'));
    }
}

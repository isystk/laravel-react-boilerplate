<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

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
            'name'  => 'admin1',
            'email' => 'admin1@test.com',
            'role'  => AdminRole::Manager->value,
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
                ->get(route('admin.user'))
                ->assertStatus($case['status']);
        }
    }
}

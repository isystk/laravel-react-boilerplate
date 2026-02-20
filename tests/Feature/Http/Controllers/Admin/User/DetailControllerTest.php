<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class DetailControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_show(): void
    {
        $user1 = $this->createDefaultUser([
            'name'  => 'user1',
            'email' => 'user1@test.com',
        ]);

        $admin1 = $this->createDefaultAdmin([
            'name'  => '管理者A',
            'email' => 'admin1@test.com',
            'role'  => AdminRole::HighManager,
        ]);
        $this->actingAs($admin1, 'admin');

        $response = $this->get(route('admin.user.show', $user1));
        $response->assertSuccessful();
        $response->assertSee('user1');
        $response->assertSee('user1@test.com');
    }

    public function test_show_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::HighManager, 'status' => 200],
            ['role' => AdminRole::Manager,     'status' => 200],
        ];

        $user = $this->createDefaultUser();

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.user.show', $user))
                ->assertStatus($case['status']);
        }
    }

    public function test_suspend(): void
    {
        $user1 = $this->createDefaultUser([
            'name'  => 'user1',
            'email' => 'user1@test.com',
        ]);

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->put(route('admin.user.suspend', $user1));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->put(route('admin.user.suspend', $user1));
        $response         = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データのステータスが「アカウント停止」になったことをテスト
        $this->assertDatabaseHas('users', [
            'id'     => $user1->id,
            'status' => \App\Enums\UserStatus::Suspended->value,
        ]);
    }

    public function test_activate(): void
    {
        $user1 = $this->createDefaultUser([
            'name'   => 'user1',
            'email'  => 'user1@test.com',
            'status' => \App\Enums\UserStatus::Suspended->value,
        ]);

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin1, 'admin');

        $redirectResponse = $this->put(route('admin.user.activate', $user1));
        $response         = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データのステータスが「有効」になったことをテスト
        $this->assertDatabaseHas('users', [
            'id'     => $user1->id,
            'status' => \App\Enums\UserStatus::Active->value,
        ]);
    }
}

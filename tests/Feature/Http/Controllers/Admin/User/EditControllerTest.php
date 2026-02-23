<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

use App\Enums\AdminRole;
use App\Services\Admin\User\UpdateService;
use Exception;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class EditControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_edit(): void
    {
        $user1 = $this->createDefaultUser([
            'name'  => 'user1',
            'email' => 'user1@test.com',
        ]);

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.user.edit', $user1));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.user.edit', $user1));
        $response->assertSuccessful();
    }

    public function test_edit_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::HighManager, 'status' => 200],
            ['role' => AdminRole::Manager,     'status' => 403],
        ];

        $user1 = $this->createDefaultUser();

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.user.edit', $user1))
                ->assertStatus($case['status']);
        }
    }

    public function test_update(): void
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
        $response = $this->put(route('admin.user.update', $user1), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->put(route('admin.user.update', $user1), [
            'name'  => 'userA',
            'email' => 'userA@test.com',
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが更新されたことをテスト
        $this->assertDatabaseHas('users', [
            'id'    => $user1->id,
            'name'  => 'userA',
            'email' => 'userA@test.com',
        ]);
    }

    public function test_edit_not_found(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $this->get(route('admin.user.edit', ['user' => 999]))
            ->assertNotFound();
    }

    public function test_update_not_found(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $this->put(route('admin.user.update', ['user' => 999]), [
            'name'  => 'userA',
            'email' => 'userA@test.com',
        ])->assertNotFound();
    }

    public function test_update_validation_error(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $user = $this->createDefaultUser();

        $response = $this->put(route('admin.user.update', $user), [
            'name'  => '',
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_guest_cannot_access(): void
    {
        $user = $this->createDefaultUser();

        $this->get(route('admin.user.edit', $user))
            ->assertRedirect(route('login'));

        $this->put(route('admin.user.update', $user))
            ->assertRedirect(route('login'));
    }

    public function test_update_service_error(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $user = $this->createDefaultUser();

        $this->mock(UpdateService::class, function ($mock) {
            $mock->shouldReceive('update')->andThrow(new Exception('Service Error'));
        });

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Service Error');

        $this->put(route('admin.user.update', $user), [
            'name'  => 'userA',
            'email' => 'userA@test.com',
        ]);
    }
}

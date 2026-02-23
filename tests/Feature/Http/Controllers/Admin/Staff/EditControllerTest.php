<?php

namespace Tests\Feature\Http\Controllers\Admin\Staff;

use App\Enums\AdminRole;
use App\Services\Admin\Staff\UpdateService;
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
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.staff.edit', $admin1));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.staff.edit', $admin1));
        $response->assertSuccessful();
    }

    public function test_edit_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::HighManager, 'status' => 200],
            ['role' => AdminRole::Manager,     'status' => 403],
        ];

        $staffA = $this->createDefaultAdmin();

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.staff.edit', $staffA))
                ->assertStatus($case['status']);
        }
    }

    public function test_update(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name'  => '管理者1',
            'email' => 'admin1@test.com',
            'role'  => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->put(route('admin.staff.update', $admin1), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name'  => '管理者2',
            'email' => 'admin2@test.com',
            'role'  => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->put(route('admin.staff.update', $admin1), [
            'name'  => '管理者A',
            'email' => 'adminA@test.com',
            'role'  => AdminRole::HighManager->value,
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが更新されたことをテスト
        $this->assertDatabaseHas('admins', [
            'id'    => $admin1->id,
            'name'  => '管理者A',
            'email' => 'adminA@test.com',
            'role'  => AdminRole::HighManager,
        ]);
    }

    public function test_edit_not_found(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $this->get(route('admin.staff.edit', ['staff' => 999]))
            ->assertNotFound();
    }

    public function test_update_not_found(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $this->put(route('admin.staff.update', ['staff' => 999]), [
            'name'  => '管理者A',
            'email' => 'adminA@test.com',
            'role'  => AdminRole::HighManager->value,
        ])->assertNotFound();
    }

    public function test_update_validation_error(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $staff = $this->createDefaultAdmin();

        $response = $this->put(route('admin.staff.update', $staff), [
            'name'  => '',
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_guest_cannot_access(): void
    {
        $staff = $this->createDefaultAdmin();

        $this->get(route('admin.staff.edit', $staff))
            ->assertRedirect(route('login'));

        $this->put(route('admin.staff.update', $staff))
            ->assertRedirect(route('login'));
    }

    public function test_update_service_error(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $staff = $this->createDefaultAdmin();

        $this->mock(UpdateService::class, function ($mock) {
            $mock->shouldReceive('update')->andThrow(new Exception('Service Error'));
        });

        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Service Error');

        $this->put(route('admin.staff.update', $staff), [
            'name'  => '管理者A',
            'email' => 'adminA@test.com',
            'role'  => AdminRole::HighManager->value,
        ]);
    }
}

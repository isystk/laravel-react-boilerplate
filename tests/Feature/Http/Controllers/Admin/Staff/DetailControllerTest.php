<?php

namespace Tests\Feature\Http\Controllers\Admin\Staff;

use App\Enums\AdminRole;
use App\Services\Admin\Staff\DestroyService;
use Exception;
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
        $admin1 = $this->createDefaultAdmin([
            'name'  => '管理者A',
            'email' => 'admin1@test.com',
            'role'  => AdminRole::SuperAdmin,
        ]);
        $this->actingAs($admin1, 'admin');

        $response = $this->get(route('admin.staff.show', $admin1));
        $response->assertSuccessful();
        $response->assertSee('管理者A');
        $response->assertSee('admin1@test.com');
        $response->assertSee('システム管理者');
    }

    public function test_show_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::SuperAdmin, 'status' => 200],
            ['role' => AdminRole::Staff,     'status' => 200],
        ];

        $staff = $this->createDefaultAdmin();

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.staff.show', $staff))
                ->assertStatus($case['status']);
        }
    }

    public function test_destroy(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => AdminRole::Staff,
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->delete(route('admin.staff.destroy', $admin1));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::SuperAdmin,
        ]);
        $this->actingAs($admin2, 'admin');

        // 自分は削除できないことのテスト
        $response = $this->delete(route('admin.staff.destroy', $admin2));
        $response->assertSessionHasErrors('errors', '自分自身を削除することはできません');

        // 自分以外は削除出来ることのテスト
        $redirectResponse = $this->delete(route('admin.staff.destroy', $admin1));
        $response         = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('admins', ['id' => $admin1->id]);
    }

    public function test_show_not_found(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::SuperAdmin,
        ]);
        $this->actingAs($admin, 'admin');

        $this->get(route('admin.staff.show', ['staff' => 999]))
            ->assertNotFound();
    }

    public function test_destroy_not_found(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::SuperAdmin,
        ]);
        $this->actingAs($admin, 'admin');

        $this->delete(route('admin.staff.destroy', ['staff' => 999]))
            ->assertNotFound();
    }

    public function test_guest_cannot_access(): void
    {
        $staff = $this->createDefaultAdmin();

        $this->get(route('admin.staff.show', $staff))
            ->assertRedirect(route('login'));

        $this->delete(route('admin.staff.destroy', $staff))
            ->assertRedirect(route('login'));
    }

    public function test_destroy_service_error(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::SuperAdmin,
        ]);
        $this->actingAs($admin, 'admin');

        $staff = $this->createDefaultAdmin();

        $this->mock(DestroyService::class, function ($mock) {
            $mock->shouldReceive('delete')->andThrow(new Exception('Service Error'));
        });

        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Service Error');

        $this->delete(route('admin.staff.destroy', $staff));
    }
}

<?php

namespace Http\Controllers\Admin\Staff;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * スタッフ新規登録画面表示のテスト
     */
    public function test_create(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => 'manager',
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.staff.create'));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => 'high-manager',
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.staff.create'));
        $response->assertSuccessful();
    }

    /**
     * スタッフ新規登録画面 登録のテスト
     */
    public function test_store(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => 'manager',
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->post(route('admin.staff.store'), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => 'high-manager',
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->post(route('admin.staff.store'), [
            'name' => '管理者3',
            'email' => 'admin3@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'manager',
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが登録されたことをテスト
        $this->assertDatabaseHas('admins', [
            'name' => '管理者3',
            'email' => 'admin3@test.com',
            //            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);
    }
}

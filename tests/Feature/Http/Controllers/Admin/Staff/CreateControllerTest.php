<?php

namespace Feature\Http\Controllers\Admin\Staff;

use App\Domain\Entities\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateControllerTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * スタッフ新規登録画面表示のテスト
     */
    public function testCreate(): void
    {
        /** @var Admin $admin1 */
        $admin1 = Admin::factory()->create([
            'name' => '管理者1',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.staff.create'));
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.staff.create'));
        $response->assertSuccessful();
    }

    /**
     * スタッフ新規登録画面 登録のテスト
     */
    public function testStore(): void
    {
        /** @var Admin $admin1 */
        $admin1 = Admin::factory()->create([
            'name' => '管理者1',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->post(route('admin.staff.store'), []);
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => 'high-manager'
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

<?php

namespace Feature\Http\Controllers\Admin\Staff;

use App\Domain\Entities\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditControllerTest extends TestCase
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
     * スタッフ編集画面表示のテスト
     */
    public function testEdit(): void
    {
        /** @var Admin $admin1 */
        $admin1 = Admin::factory()->create([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.staff.edit', $admin1));
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.staff.edit', $admin1));
        $response->assertSuccessful();
    }

    /**
     * スタッフ編集画面 変更のテスト
     */
    public function testUpdate(): void
    {
        /** @var Admin $admin1 */
        $admin1 = Admin::factory()->create([
            'name' => '管理者1',
            'email' => 'admin1@test.com',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->post(route('admin.staff.update', $admin1), []);
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'email' => 'admin2@test.com',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->post(route('admin.staff.update', $admin1), [
            'name' => '管理者A',
            'email' => 'adminA@test.com',
            'role' => 'high-manager'
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが更新されたことをテスト
        $this->assertDatabaseHas('admins', [
            'id' => $admin1->id,
            'name' => '管理者A',
            'email' => 'adminA@test.com',
            'role' => 'high-manager'
        ]);
    }

}

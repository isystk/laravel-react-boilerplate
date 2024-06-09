<?php

namespace Feature\Http\Controllers\Admin\User;

use App\Domain\Entities\Admin;
use App\Domain\Entities\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
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
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * ユーザー編集画面表示のテスト
     */
    public function testEdit(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ]);

        /** @var Admin $admin1 */
        $admin1 = Admin::factory()->create([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.user.edit', $user1));
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.user.edit', $user1));
        $response->assertSuccessful();
    }

    /**
     * ユーザー編集画面 変更のテスト
     */
    public function testUpdate(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ]);

        /** @var Admin $admin1 */
        $admin1 = Admin::factory()->create([
            'name' => '管理者1',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->put(route('admin.user.update', $user1), []);
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->put(route('admin.user.update', $user1), [
            'name' => 'userA',
            'email' => 'userA@test.com',
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが更新されたことをテスト
        $this->assertDatabaseHas('users', [
            'id' => $user1->id,
            'name' => 'userA',
            'email' => 'userA@test.com',
        ]);
    }

}

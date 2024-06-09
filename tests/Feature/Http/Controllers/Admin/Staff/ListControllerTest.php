<?php

namespace Feature\Http\Controllers\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListControllerTest extends TestCase
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
     * スタッフ一覧画面表示のテスト
     */
    public function testIndex(): void
    {

        /** @var Admin $admin1 */
        $admin1 = Admin::factory([
            'name' => 'user1',
            'email' => 'user1@test.com',
            'role' => AdminRole::HighManager->value
        ])->create();
        /** @var Admin $admin2 */
        $admin2 = Admin::factory([
            'name' => 'user2',
            'email' => 'user2@test.com',
            'role' => AdminRole::Manager->value
        ])->create();
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.staff'));
        $response->assertSuccessful();
        $response->assertSeeInOrder(['user1', 'user2']);
    }

}

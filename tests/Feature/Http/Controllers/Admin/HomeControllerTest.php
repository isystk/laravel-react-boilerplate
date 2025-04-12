<?php

namespace Feature\Http\Controllers\Admin;

use App\Domain\Entities\Admin;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
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
     * ホーム画面表示のテスト
     */
    public function testIndex(): void
    {
        /** @var Admin $admin */
        $admin = Admin::factory()->create([
            'name' => '管理者A',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.home'));
        $response->assertSee('ようこそ！管理者A');
    }
}

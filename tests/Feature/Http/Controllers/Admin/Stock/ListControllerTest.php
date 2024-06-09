<?php

namespace Feature\Http\Controllers\Admin\Stock;

use App\Domain\Entities\Admin;
use App\Domain\Entities\Stock;
use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
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
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * 商品一覧画面表示のテスト
     */
    public function testIndex(): void
    {
        Stock::factory(['name' => 'stock1'])->create();
        Stock::factory(['name' => 'stock2'])->create();

        /** @var Admin $admin */
        $admin = Admin::factory([
            'name' => 'admin1',
            'role' => AdminRole::Manager->value
        ])->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.stock'));
        $response->assertSuccessful();
        $response->assertSeeInOrder(['stock2', 'stock1']);
    }

}

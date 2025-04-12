<?php

namespace Feature\Http\Controllers\Admin\Order;

use App\Domain\Entities\Admin;
use App\Domain\Entities\Order;
use App\Domain\Entities\OrderStock;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class DetailControllerTest extends TestCase
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
     * 注文詳細画面表示のテスト
     */
    public function testShow(): void
    {
        /** @var Admin $admin */
        $admin = Admin::factory()->create([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin, 'admin');

        /** @var User $user1 */
        $user1 = User::factory(['name' => 'user1'])->create();

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => '商品1'])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => '商品2'])->create();

        /** @var Order $order1 */
        $order1 = Order::factory(['user_id' => $user1->id, 'created_at' => '2024-05-01'])->create();
        OrderStock::factory(['order_id' => $order1->id, 'stock_id' => $stock1->id])->create();
        OrderStock::factory(['order_id' => $order1->id, 'stock_id' => $stock2->id])->create();

        $response = $this->get(route('admin.order.show', $order1));
        $response->assertSuccessful();
        $response->assertSee('user1');
        $response->assertSee('商品1');
        $response->assertSee('商品2');
    }

}

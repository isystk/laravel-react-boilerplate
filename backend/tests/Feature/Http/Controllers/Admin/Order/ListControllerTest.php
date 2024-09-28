<?php

namespace Feature\Http\Controllers\Admin\Order;

use App\Domain\Entities\Admin;
use App\Domain\Entities\Order;
use App\Domain\Entities\OrderStock;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
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
     * 注文一覧画面表示のテスト
     */
    public function testIndex(): void
    {
        /** @var Admin $admin */
        $admin = Admin::factory()->create([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin, 'admin');

        /** @var User $user1 */
        $user1 = User::factory(['name' => 'user1'])->create();
        /** @var User $user2 */
        $user2 = User::factory(['name' => 'user2'])->create();

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => '商品1'])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => '商品2'])->create();
        /** @var Stock $stock3 */
        $stock3 = Stock::factory(['name' => '商品3'])->create();

        /** @var Order $order1 */
        $order1 = Order::factory(['user_id' => $user1->id, 'created_at' => '2024-05-01'])->create();
        OrderStock::factory(['order_id' => $order1->id, 'stock_id' => $stock1->id])->create();
        OrderStock::factory(['order_id' => $order1->id, 'stock_id' => $stock2->id])->create();
        /** @var Order $order2 */
        $order2 = Order::factory(['user_id' => $user2->id, 'created_at' => '2024-06-01'])->create();
        OrderStock::factory(['order_id' => $order2->id, 'stock_id' => $stock3->id])->create();
        $response = $this->get(route('admin.order') . '?sort_name=id&sort_direction=asc');
        $response->assertSuccessful();
        $response->assertSeeInOrder(['user1', 'user2']);
    }

}

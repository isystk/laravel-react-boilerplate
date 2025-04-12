<?php

namespace Services\Api\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Entities\Order;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use App\Services\Api\Cart\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class CheckoutServiceTest extends TestCase
{

    use RefreshDatabase;

    private CheckoutService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CheckoutService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(CheckoutService::class, $this->service);
    }

    /**
     * checkoutのテスト
     */
    public function testCheckout(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create([
            'name' => 'aaa',
            'email' => 'aaa@test.com',
        ]);
        // ユーザをログイン状態にする
        $this->actingAs($user1);

        $result = $this->service->getMyCart();
        $this->assertCount(0, $result['data'], 'カートに追加した商品がない状態でエラーにならないことを始めにテスト');

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1', 'price' => 111, 'quantity' => 1])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2', 'price' => 222, 'quantity' => 100])->create();

        Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock1->id])->create();
        Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock2->id])->create();
        Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock2->id])->create();

        $stripeEmail = '';
        $stripeToken = '';
        $this->service->checkout($stripeEmail, $stripeToken);

        // 注文データが登録されたことをテスト
        $order = Order::where('user_id', $user1->id)->first();
        $this->assertDatabaseHas('orders', ['user_id' => $user1->id, 'sum_price' => 555]);
        $this->assertDatabaseHas('order_stocks', ['order_id' => $order->id, 'stock_id' => $stock1->id, 'price' => 111, 'quantity' => 1]);
        $this->assertDatabaseHas('order_stocks', ['order_id' => $order->id, 'stock_id' => $stock2->id, 'price' => 222, 'quantity' => 1]);

        // 商品の在庫が減っていること
        $afterStock1 = Stock::find($stock1->id);
        $afterStock2 = Stock::find($stock2->id);
        $this->assertEquals(0, $afterStock1->quantity);
        $this->assertEquals(98, $afterStock2->quantity);
    }
}

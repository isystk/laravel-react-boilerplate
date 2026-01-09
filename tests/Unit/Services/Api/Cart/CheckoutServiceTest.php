<?php

namespace Services\Api\Cart;

use App\Domain\Entities\Order;
use App\Domain\Entities\Stock;
use App\Services\Api\Cart\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class CheckoutServiceTest extends BaseTest
{
    use RefreshDatabase;

    private CheckoutService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CheckoutService::class);
    }

    public function test_checkout(): void
    {
        $user1 = $this->createDefaultUser([
            'name'  => 'aaa',
            'email' => 'aaa@test.com',
        ]);
        // ユーザをログイン状態にする
        $this->actingAs($user1);

        $dto = $this->service->getMyCart();
        $this->assertCount(0, $dto->stocks, 'カートに追加した商品がない状態でエラーにならないことを始めにテスト');

        $stock1 = $this->createDefaultStock(['name' => 'stock1', 'price' => 111, 'quantity' => 1]);
        $stock2 = $this->createDefaultStock(['name' => 'stock2', 'price' => 222, 'quantity' => 100]);

        $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock1->id]);
        $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock2->id]);
        $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock2->id]);

        $stripeEmail = '';
        $stripeToken = '';
        $this->service->checkout($stripeEmail, $stripeToken);

        // 注文データが登録されたことをテスト
        $order = Order::where('user_id', $user1->id)->first();
        $this->assertDatabaseHas('orders', ['user_id' => $user1->id, 'sum_price' => 555]);
        $this->assertDatabaseHas('order_stocks',
            ['order_id' => $order->id, 'stock_id' => $stock1->id, 'price' => 111, 'quantity' => 1]);
        $this->assertDatabaseHas('order_stocks',
            ['order_id' => $order->id, 'stock_id' => $stock2->id, 'price' => 222, 'quantity' => 1]);

        // 商品の在庫が減っていること
        $afterStock1 = Stock::find($stock1->id);
        $afterStock2 = Stock::find($stock2->id);
        $this->assertEquals(0, $afterStock1->quantity);
        $this->assertEquals(98, $afterStock2->quantity);
    }
}

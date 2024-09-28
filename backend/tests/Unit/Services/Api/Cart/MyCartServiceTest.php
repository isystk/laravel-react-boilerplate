<?php

namespace Tests\Unit\Services\Api\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use App\Services\Api\Cart\MyCartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyCartServiceTest extends TestCase
{

    use RefreshDatabase;

    private MyCartService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(MyCartService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(MyCartService::class, $this->service);
    }

    /**
     * getMyCartのテスト
     */
    public function testGetMyCart(): void
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
        $stock1 = Stock::factory(['name' => 'stock1', 'price' => 111])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2', 'price' => 222])->create();

        Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock1->id])->create();
        Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock2->id])->create();

        $result = $this->service->getMyCart();
        $stockIds = collect($result['data'])->pluck('stock_id')->all();
        $this->assertSame([$stock1->id, $stock2->id], $stockIds, 'カートに追加した商品をテスト');
        $this->assertSame(2, $result['count'], 'カートに追加した商品の総数をテスト');
        $this->assertSame(333, $result['sum'], 'カートに追加した商品の総価格をテスト');
    }
}

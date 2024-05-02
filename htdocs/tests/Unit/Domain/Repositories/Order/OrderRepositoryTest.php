<?php

namespace Domain\Repositories\Order;

use App\Domain\Entities\Order;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use App\Domain\Repositories\Order\OrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderRepositoryTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    private OrderRepository $repository;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(OrderRepository::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(OrderRepository::class, $this->repository);
    }

    /**
     * getConditionsWithUserStockのテスト
     */
    public function testGetConditionsWithUserStock(): void
    {
        $defaultConditions = [
            'user_name' => null,
            'limit' => null,
        ];

        $users = $this->repository->getConditionsWithUserStock($defaultConditions);
        $this->assertSame(0, $users->count(), 'データがない状態で正常に動作することを始めにテスト');

        /** @var User $user1 */
        $user1 = User::factory(['name' => 'user1', 'email' => 'user1@test.com'])->create();
        /** @var User $user2 */
        $user2 = User::factory(['name' => 'user2', 'email' => 'user2@test.com'])->create();

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1'])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2'])->create();

        /** @var Order $fitStock1 */
        $fitStock1 = Order::factory(['user_id' => $user1->id, 'stock_id' => $stock1->id])->create();
        /** @var Order $fitStock2 */
        $fitStock2 = Order::factory(['user_id' => $user1->id, 'stock_id' => $stock2->id])->create();
        /** @var Order $unfitStock */
        $unfitStock = Order::factory(['user_id' => $user2->id, 'stock_id' => $stock1->id])->create();
        $expectOrderIds = [$fitStock1->id, $fitStock2->id];

        $orders = $this->repository->getConditionsWithUserStock([
            ...$defaultConditions,
            'user_name' => $user1->name
        ]);
        $orderIds = $orders->pluck('id')->all();
        $this->assertSame($expectOrderIds, $orderIds, 'user_nameで検索が出来ることをテスト');
    }
}

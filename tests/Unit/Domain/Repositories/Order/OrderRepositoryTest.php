<?php

namespace Domain\Repositories\Order;

use App\Domain\Entities\Order;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use App\Domain\Repositories\Order\OrderRepository;
use App\Utils\DateUtil;
use Carbon\CarbonImmutable;
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
            'order_date_from' => null,
            'order_date_to' => null,
            'sort_name' => null,
            'sort_direction' => null,
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

        /** @var Order $order1 */
        $order1 = Order::factory(['user_id' => $user1->id, 'stock_id' => $stock1->id, 'created_at' => '2024-04-01'])->create();
        /** @var Order $order2 */
        $order2 = Order::factory(['user_id' => $user1->id, 'stock_id' => $stock2->id, 'created_at' => '2024-05-01'])->create();
        /** @var Order $order3 */
        $order3 = Order::factory(['user_id' => $user2->id, 'stock_id' => $stock1->id, 'created_at' => '2024-06-01'])->create();
        $orders = $this->repository->getConditionsWithUserStock([
            ...$defaultConditions,
            'user_name' => $user1->name
        ]);
        $orderIds = $orders->pluck('id')->all();
        $this->assertSame([$order1->id, $order2->id], $orderIds, 'user_nameで検索が出来ることをテスト');

        $orders = $this->repository->getConditionsWithUserStock([
            ...$defaultConditions,
            'order_date_from' => DateUtil::toCarbonImmutable('2024-06-01 00:00:00'),
            'order_date_to' => DateUtil::toCarbonImmutable('2024-06-01 23:59:59'),
        ]);
        $orderIds = $orders->pluck('id')->all();
        $this->assertSame([$order3->id], $orderIds, 'order_dateで検索が出来ることをテスト');
    }
}

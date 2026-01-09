<?php

namespace Domain\Repositories\Order;

use App\Domain\Repositories\Order\OrderRepository;
use App\Utils\DateUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class OrderRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private OrderRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(OrderRepository::class);
    }

    public function test_getConditionsWithUserStock(): void
    {
        $defaultConditions = [
            'user_name'       => null,
            'order_date_from' => null,
            'order_date_to'   => null,
            'sort_name'       => null,
            'sort_direction'  => null,
            'limit'           => null,
        ];

        $result = $this->repository->getConditionsWithUserStock($defaultConditions);
        $this->assertSame(0, $result->count(), 'データがない状態で正常に動作することを始めにテスト');

        $user1 = $this->createDefaultUser(['name' => 'user1', 'email' => 'user1@test.com']);
        $user2 = $this->createDefaultUser(['name' => 'user2', 'email' => 'user2@test.com']);

        $stock1 = $this->createDefaultStock(['name' => 'stock1']);
        $stock2 = $this->createDefaultStock(['name' => 'stock2']);

        $order1 = $this->createDefaultOrder(['user_id' => $user1->id, 'created_at' => '2024-04-01']);
        $this->createDefaultOrderStock([
            'order_id' => $order1->id,
            'stock_id' => $stock1->id,
            'price'    => $stock1->price,
            'quantity' => 1,
        ]);
        $this->createDefaultOrderStock([
            'order_id' => $order1->id,
            'stock_id' => $stock2->id,
            'price'    => $stock2->price,
            'quantity' => 1,
        ]);
        $order2 = $this->createDefaultOrder(['user_id' => $user1->id, 'created_at' => '2024-05-01']);
        $this->createDefaultOrderStock([
            'order_id' => $order2->id,
            'stock_id' => $stock2->id,
            'price'    => $stock2->price,
            'quantity' => 1,
        ]);
        $order3 = $this->createDefaultOrder(['user_id' => $user2->id, 'created_at' => '2024-06-01']);
        $this->createDefaultOrderStock([
            'order_id' => $order3->id,
            'stock_id' => $stock1->id,
            'price'    => $stock1->price,
            'quantity' => 1,
        ]);
        $orders = $this->repository->getConditionsWithUserStock([
            ...$defaultConditions,
            'user_name' => $user1->name,
        ]);
        $orderIds = $orders->pluck('id')->all();
        $this->assertSame([$order1->id, $order2->id], $orderIds, 'user_nameで検索が出来ることをテスト');

        $orders = $this->repository->getConditionsWithUserStock([
            ...$defaultConditions,
            'order_date_from' => DateUtil::toCarbon('2024-06-01 00:00:00'),
            'order_date_to'   => DateUtil::toCarbon('2024-06-01 23:59:59'),
        ]);
        $orderIds = $orders->pluck('id')->all();
        $this->assertSame([$order3->id], $orderIds, 'order_dateで検索が出来ることをテスト');
    }
}

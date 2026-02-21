<?php

namespace Tests\Unit\Domain\Repositories\Order;

use App\Domain\Repositories\Order\OrderRepositoryInterface;
use App\Utils\DateUtil;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class OrderRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private OrderRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(OrderRepositoryInterface::class);
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

    public function test_countTodaysOrders_注文がない場合(): void
    {
        $count = $this->repository->countTodaysOrders();
        $this->assertSame(0, $count);
    }

    public function test_countTodaysOrders_本日の注文のみカウントされる(): void
    {
        $user = $this->createDefaultUser();

        $this->createDefaultOrder([
            'user_id'    => $user->id,
            'created_at' => Carbon::today(),
        ]);
        $this->createDefaultOrder([
            'user_id'    => $user->id,
            'created_at' => Carbon::today(),
        ]);
        $this->createDefaultOrder([
            'user_id'    => $user->id,
            'created_at' => Carbon::yesterday(),
        ]);

        $count = $this->repository->countTodaysOrders();
        $this->assertSame(2, $count, '本日の注文のみがカウントされること');
    }

    public function test_getLatestWithUser_注文がない場合(): void
    {
        $orders = $this->repository->getLatestWithUser(10);
        $this->assertSame(0, $orders->count());
    }

    public function test_getLatestWithUser_最新順に取得される(): void
    {
        $user = $this->createDefaultUser();

        $order1 = $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => '2024-01-01']);
        $order2 = $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => '2024-03-01']);
        $order3 = $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => '2024-02-01']);

        $orders   = $this->repository->getLatestWithUser(10);
        $orderIds = $orders->pluck('id')->all();

        $this->assertSame([$order2->id, $order3->id, $order1->id], $orderIds, '作成日時の降順で取得されること');
    }

    public function test_getLatestWithUser_件数制限が効く(): void
    {
        $user = $this->createDefaultUser();

        $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => '2024-01-01']);
        $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => '2024-02-01']);
        $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => '2024-03-01']);

        $orders = $this->repository->getLatestWithUser(2);
        $this->assertSame(2, $orders->count(), '指定した件数のみ取得されること');
    }

    public function test_getLatestWithUser_ユーザー情報がロードされる(): void
    {
        $user = $this->createDefaultUser(['name' => 'テストユーザー']);
        $this->createDefaultOrder(['user_id' => $user->id]);

        $orders = $this->repository->getLatestWithUser(10);
        $this->assertTrue($orders->first()->relationLoaded('user'), 'userリレーションがロードされていること');
        $this->assertSame('テストユーザー', $orders->first()->user->name);
    }

    public function test_deleteByUserId(): void
    {
        $user1  = $this->createDefaultUser();
        $order1 = $this->createDefaultOrder(['user_id' => $user1->id]);
        $order2 = $this->createDefaultOrder(['user_id' => $user1->id]);

        $user2  = $this->createDefaultUser();
        $order3 = $this->createDefaultOrder(['user_id' => $user2->id]);

        $this->repository->deleteByUserId($user1->id);

        // user1の注文が削除され、user2の注文は削除されないことをテスト
        $this->assertDatabaseMissing('orders', ['id' => $order1->id]);
        $this->assertDatabaseMissing('orders', ['id' => $order2->id]);
        $this->assertDatabaseHas('orders', ['id' => $order3->id]);
    }
}

<?php

namespace Tests\Unit\Domain\Repositories\Order;

use App\Domain\Repositories\Order\OrderRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class OrderRepositoryCountTodaysOrdersTest extends BaseTest
{
    use RefreshDatabase;

    private OrderRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(OrderRepository::class);
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
}

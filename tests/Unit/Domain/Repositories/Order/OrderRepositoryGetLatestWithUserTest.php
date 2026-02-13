<?php

namespace Tests\Unit\Domain\Repositories\Order;

use App\Domain\Repositories\Order\OrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class OrderRepositoryGetLatestWithUserTest extends BaseTest
{
    use RefreshDatabase;

    private OrderRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(OrderRepository::class);
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
}

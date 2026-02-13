<?php

namespace Tests\Unit\Services\Admin\Home;

use App\Services\Admin\Home\IndexService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class IndexServiceTest extends BaseTest
{
    use RefreshDatabase;

    private IndexService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    public function test_getTodaysOrdersCount_注文がない場合(): void
    {
        $count = $this->service->getTodaysOrdersCount();
        $this->assertSame(0, $count);
    }

    public function test_getTodaysOrdersCount_本日の注文のみカウントされる(): void
    {
        $user = $this->createDefaultUser();

        $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => Carbon::today()]);
        $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => Carbon::today()]);
        $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => Carbon::yesterday()]);

        $count = $this->service->getTodaysOrdersCount();
        $this->assertSame(2, $count, '本日の注文のみがカウントされること');
    }

    public function test_getSalesByMonth_データがない場合(): void
    {
        $result = $this->service->getSalesByMonth();
        $this->assertSame(0, $result->count());
    }

    public function test_getSalesByMonth_年月昇順でフォーマットされたデータが返る(): void
    {
        $this->createDefaultMonthlySale(['year_month' => '202406', 'amount' => 30000]);
        $this->createDefaultMonthlySale(['year_month' => '202404', 'amount' => 10000]);
        $this->createDefaultMonthlySale(['year_month' => '202405', 'amount' => 0]);

        $result = $this->service->getSalesByMonth();

        $this->assertSame(3, $result->count());
        $this->assertSame('202404', $result[0]['year_month']);
        $this->assertSame(10000, $result[0]['amount']);
        $this->assertSame('202405', $result[1]['year_month']);
        $this->assertSame(0, $result[1]['amount'], '0の場合は0になること');
        $this->assertSame('202406', $result[2]['year_month']);
        $this->assertSame(30000, $result[2]['amount']);
    }

    public function test_getLatestOrders_注文がない場合(): void
    {
        $orders = $this->service->getLatestOrders();
        $this->assertSame(0, $orders->count());
    }

    public function test_getLatestOrders_最新順にデフォルト10件取得される(): void
    {
        $user = $this->createDefaultUser();

        for ($i = 1; $i <= 12; $i++) {
            $this->createDefaultOrder([
                'user_id'    => $user->id,
                'created_at' => sprintf('2024-%02d-01', $i),
            ]);
        }

        $orders = $this->service->getLatestOrders();
        $this->assertSame(10, $orders->count(), 'デフォルトで10件取得されること');
        $this->assertTrue(
            $orders->first()->created_at > $orders->last()->created_at,
            '最新順で取得されること'
        );
    }

    public function test_getLatestOrders_件数を指定できる(): void
    {
        $user = $this->createDefaultUser();

        for ($i = 1; $i <= 5; $i++) {
            $this->createDefaultOrder(['user_id' => $user->id]);
        }

        $orders = $this->service->getLatestOrders(3);
        $this->assertSame(3, $orders->count(), '指定した件数のみ取得されること');
    }
}

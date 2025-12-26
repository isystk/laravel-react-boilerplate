<?php

namespace Tests\Unit\Services\Admin\Order;

use App\Domain\Entities\OrderStock;
use App\Dto\Request\Admin\Order\SearchConditionDto;
use App\Services\Admin\Order\IndexService;
use App\Utils\DateUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
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

    /**
     * searchOrderのテスト
     */
    public function test_search_order(): void
    {
        $request = new Request([
            'user_name' => null,
            'order_date_from' => null,
            'order_date_to' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
            'limit' => 20,
        ]);
        $default = new SearchConditionDto($request);

        $orders = $this->service->searchOrder($default);
        $this->assertSame(0, $orders->count(), '引数がない状態でエラーにならないことを始めにテスト');

        $user1 = $this->createDefaultUser(['name' => 'user1']);
        $user2 = $this->createDefaultUser(['name' => 'user2']);

        $stock1 = $this->createDefaultStock(['name' => '商品1']);
        $stock2 = $this->createDefaultStock(['name' => '商品2']);
        $stock3 = $this->createDefaultStock(['name' => '商品3']);

        $order1 = $this->createDefaultOrder(['user_id' => $user1->id, 'created_at' => '2024-05-01']);
        $this->createDefaultOrderStock(['order_id' => $order1->id, 'stock_id' => $stock1->id]);
        $this->createDefaultOrderStock(['order_id' => $order1->id, 'stock_id' => $stock2->id]);
        $order2 = $this->createDefaultOrder(['user_id' => $user2->id, 'created_at' => '2024-06-01']);
        OrderStock::factory(['order_id' => $order2->id, 'stock_id' => $stock3->id])->create();

        $input = clone $default;
        $input->name = 'user2';
        $orders = $this->service->searchOrder($input)->getCollection();
        $orderIds = $orders->pluck('id')->all();
        $this->assertSame([$order2->id], $orderIds, 'user_nameで検索が出来ることをテスト');

        $input = clone $default;
        $input->orderDateFrom = DateUtil::toCarbon('2024-06-01');
        $orders = $this->service->searchOrder($input)->getCollection();
        $orderIds = $orders->pluck('id')->all();
        $this->assertContains($order2->id, $orderIds, 'order_date_fromで検索が出来ることをテスト');

        $input = clone $default;
        $input->orderDateTo = DateUtil::toCarbon('2024-05-01');
        $orders = $this->service->searchOrder($input)->getCollection();
        $orderIds = $orders->pluck('id')->all();
        $this->assertSame([$order1->id], $orderIds, 'order_date_toで検索が出来ることをテスト');

        $input = clone $default;
        $input->sortName = 'id';
        $input->sortDirection = 'desc';
        $orders = $this->service->searchOrder($input)->getCollection();
        $orderIds = $orders->pluck('id')->all();
        $this->assertContains($order1->id, $orderIds, 'ソート指定で検索が出来ることをテスト');
        $this->assertContains($order2->id, $orderIds, 'ソート指定で検索が出来ることをテスト');
    }
}

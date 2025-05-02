<?php

namespace Tests\Unit\Services\Admin\Order;

use App\Domain\Entities\Order;
use App\Domain\Entities\OrderStock;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use App\Services\Admin\Order\IndexService;
use App\Utils\DateUtil;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexServiceTest extends TestCase
{

    use RefreshDatabase;

    private IndexService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    /**
     * searchOrderのテスト
     */
    public function testSearchOrder(): void
    {
        $default = [
            'user_name' => null,
            'order_date_from' => null,
            'order_date_to' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
            'limit' => 20,
        ];

        $orders = $this->service->searchOrder($default);
        $this->assertSame(0, $orders->count(), '引数がない状態でエラーにならないことを始めにテスト');

        /** @var User $user1 */
        $user1 = User::factory(['name' => 'user1'])->create();
        /** @var User $user2 */
        $user2 = User::factory(['name' => 'user2'])->create();

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => '商品1'])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => '商品2'])->create();
        /** @var Stock $stock3 */
        $stock3 = Stock::factory(['name' => '商品3'])->create();

        /** @var Order $order1 */
        $order1 = Order::factory(['user_id' => $user1->id, 'created_at' => '2024-05-01'])->create();
        OrderStock::factory(['order_id' => $order1->id, 'stock_id' => $stock1->id])->create();
        OrderStock::factory(['order_id' => $order1->id, 'stock_id' => $stock2->id])->create();
        /** @var Order $order2 */
        $order2 = Order::factory(['user_id' => $user2->id, 'created_at' => '2024-06-01'])->create();
        OrderStock::factory(['order_id' => $order2->id, 'stock_id' => $stock3->id])->create();

        $input = $default;
        $input['user_name'] = 'user2';
        /** @var LengthAwarePaginator<int, Order> $orders */
        $orders = $this->service->searchOrder($input);
        $orderIds = collect($orders->items())->pluck('id')->all();
        $this->assertSame([$order2->id], $orderIds, 'user_nameで検索が出来ることをテスト');

        $input = $default;
        $input['order_date_from'] = DateUtil::toCarbonImmutable('2024-06-01');
        /** @var LengthAwarePaginator<int, Order> $orders */
        $orders = $this->service->searchOrder($input);
        $orderIds = collect($orders->items())->pluck('id')->all();
        $this->assertSame([$order2->id], $orderIds, 'order_date_fromで検索が出来ることをテスト');

        $input = $default;
        $input['order_date_to'] = DateUtil::toCarbonImmutable('2024-05-01');
        /** @var LengthAwarePaginator<int, Order> $orders */
        $orders = $this->service->searchOrder($input);
        $orderIds = collect($orders->items())->pluck('id')->all();
        $this->assertSame([$order1->id], $orderIds, 'order_date_toで検索が出来ることをテスト');

        $input = $default;
        $input['sort_name'] = 'id';
        $input['sort_direction'] = 'desc';
        /** @var LengthAwarePaginator<int, Order> $orders */
        $orders = $this->service->searchOrder($input);
        $orderIds = collect($orders->items())->pluck('id')->all();
        $this->assertSame([$order2->id, $order1->id], $orderIds, 'ソート指定で検索が出来ることをテスト');

    }
}

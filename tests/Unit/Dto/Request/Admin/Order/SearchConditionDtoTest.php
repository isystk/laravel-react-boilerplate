<?php

namespace Tests\Unit\Dto\Request\Admin\Order;

use App\Dto\Request\Admin\Order\SearchConditionDto;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Tests\BaseTest;

class SearchConditionDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = Request::create('/', 'GET', [
            'name'            => 'テスト注文',
            'order_date_from' => '2024-01-01',
            'order_date_to'   => '2024-12-31',
            'sort_name'       => 'name',
            'sort_direction'  => 'asc',
            'page'            => '2',
            'limit'           => '15',
        ]);

        $dto = new SearchConditionDto($request);

        $this->assertSame('テスト注文', $dto->name);
        $this->assertInstanceOf(CarbonImmutable::class, $dto->orderDateFrom);
        $this->assertSame('2024-01-01 00:00:00', $dto->orderDateFrom->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(CarbonImmutable::class, $dto->orderDateTo);
        $this->assertSame('2024-12-31 00:00:00', $dto->orderDateTo->format('Y-m-d H:i:s'));
        $this->assertSame('name', $dto->sortName);
        $this->assertSame('asc', $dto->sortDirection);
        $this->assertSame(2, $dto->page);
        $this->assertSame(15, $dto->limit);
    }

    public function test_construct_日付が未指定の場合nullになること(): void
    {
        $request = Request::create('/', 'GET');

        $dto = new SearchConditionDto($request);

        $this->assertNull($dto->name);
        $this->assertNull($dto->orderDateFrom);
        $this->assertNull($dto->orderDateTo);
    }

    public function test_construct_デフォルト値が正しく設定されること(): void
    {
        $request = Request::create('/', 'GET');

        $dto = new SearchConditionDto($request);

        $this->assertSame('id', $dto->sortName);
        $this->assertSame('desc', $dto->sortDirection);
        $this->assertSame(1, $dto->page);
        $this->assertSame(20, $dto->limit);
    }

    /**
     * @testWith ["invalid"]
     *           ["ASC"]
     *           [""]
     */
    public function test_construct_sortDirectionが不正な値の場合descになること(string $direction): void
    {
        $request = Request::create('/', 'GET', [
            'sort_direction' => $direction,
        ]);

        $dto = new SearchConditionDto($request);

        $this->assertSame('desc', $dto->sortDirection);
    }
}

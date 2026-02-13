<?php

namespace Tests\Unit\Dto\Request\Admin\Stock;

use App\Dto\Request\Admin\Stock\SearchConditionDto;
use Illuminate\Http\Request;
use Tests\BaseTest;

class SearchConditionDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = Request::create('/', 'GET', [
            'name'           => 'テスト商品',
            'sort_name'      => 'name',
            'sort_direction' => 'asc',
            'page'           => '3',
            'limit'          => '10',
        ]);

        $dto = new SearchConditionDto($request);

        $this->assertSame('テスト商品', $dto->name);
        $this->assertSame('name', $dto->sortName);
        $this->assertSame('asc', $dto->sortDirection);
        $this->assertSame(3, $dto->page);
        $this->assertSame(10, $dto->limit);
    }

    public function test_construct_nameが未指定の場合nullになること(): void
    {
        $request = Request::create('/', 'GET');

        $dto = new SearchConditionDto($request);

        $this->assertNull($dto->name);
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

<?php

namespace Tests\Unit\Dto\Request\Admin\User;

use App\Dto\Request\Admin\User\SearchConditionDto;
use Illuminate\Http\Request;
use Tests\BaseTest;

class SearchConditionDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = Request::create('/', 'GET', [
            'name'           => 'テストユーザー',
            'email'          => 'test@example.com',
            'sort_name'      => 'email',
            'sort_direction' => 'asc',
            'page'           => '2',
            'limit'          => '50',
        ]);

        $dto = new SearchConditionDto($request);

        $this->assertSame('テストユーザー', $dto->name);
        $this->assertSame('test@example.com', $dto->email);
        $this->assertSame('email', $dto->sortName);
        $this->assertSame('asc', $dto->sortDirection);
        $this->assertSame(2, $dto->page);
        $this->assertSame(50, $dto->limit);
    }

    public function test_construct_nameとemailが未指定の場合nullになること(): void
    {
        $request = Request::create('/', 'GET');

        $dto = new SearchConditionDto($request);

        $this->assertNull($dto->name);
        $this->assertNull($dto->email);
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

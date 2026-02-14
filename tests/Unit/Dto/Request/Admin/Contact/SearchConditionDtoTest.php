<?php

namespace Tests\Unit\Dto\Request\Admin\Contact;

use App\Dto\Request\Admin\Contact\SearchConditionDto;
use Illuminate\Http\Request;
use Tests\BaseTest;

class SearchConditionDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = Request::create('/', 'GET', [
            'user_name'      => 'テストユーザー',
            'title'          => 'お問い合わせ件名',
            'sort_name'      => 'title',
            'sort_direction' => 'asc',
            'page'           => '5',
            'limit'          => '30',
        ]);

        $dto = new SearchConditionDto($request);

        $this->assertSame('テストユーザー', $dto->userName);
        $this->assertSame('お問い合わせ件名', $dto->title);
        $this->assertSame('title', $dto->sortName);
        $this->assertSame('asc', $dto->sortDirection);
        $this->assertSame(5, $dto->page);
        $this->assertSame(30, $dto->limit);
    }

    public function test_construct_userNameとtitleが未指定の場合nullになること(): void
    {
        $request = Request::create('/', 'GET');

        $dto = new SearchConditionDto($request);

        $this->assertNull($dto->userName);
        $this->assertNull($dto->title);
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

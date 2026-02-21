<?php

namespace Tests\Unit\Dto\Request\Admin\User;

use App\Dto\Request\Admin\User\SearchConditionDto;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Tests\BaseTest;

class SearchConditionDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = Request::create('/', 'GET', [
            'keyword'        => 'テストユーザー',
            'status'         => '0',
            'has_google'     => '1',
            'with_trashed'   => '1',
            'sort_name'      => 'email',
            'sort_direction' => 'asc',
            'page'           => '2',
            'limit'          => '50',
        ]);

        $dto = new SearchConditionDto($request);

        $this->assertSame('テストユーザー', $dto->keyword);
        $this->assertSame(UserStatus::Active, $dto->status);
        $this->assertTrue($dto->hasGoogle);
        $this->assertTrue($dto->withTrashed);
        $this->assertSame('email', $dto->sortName);
        $this->assertSame('asc', $dto->sortDirection);
        $this->assertSame(2, $dto->page);
        $this->assertSame(50, $dto->limit);
    }

    public function test_construct_keywordとstatusとhas_googleが未指定の場合nullになること(): void
    {
        $request = Request::create('/', 'GET');

        $dto = new SearchConditionDto($request);

        $this->assertNull($dto->keyword);
        $this->assertNull($dto->status);
        $this->assertNull($dto->hasGoogle);
    }

    public function test_construct_デフォルト値が正しく設定されること(): void
    {
        $request = Request::create('/', 'GET');

        $dto = new SearchConditionDto($request);

        $this->assertFalse($dto->withTrashed);
        $this->assertSame('id', $dto->sortName);
        $this->assertSame('desc', $dto->sortDirection);
        $this->assertSame(1, $dto->page);
        $this->assertSame(20, $dto->limit);
    }

    public function test_construct_with_trashedが1の場合trueになること(): void
    {
        $request = Request::create('/', 'GET', ['with_trashed' => '1']);

        $dto = new SearchConditionDto($request);

        $this->assertTrue($dto->withTrashed);
    }

    public function test_construct_with_trashedが未指定の場合falseになること(): void
    {
        $request = Request::create('/', 'GET');

        $dto = new SearchConditionDto($request);

        $this->assertFalse($dto->withTrashed);
    }

    public function test_construct_keywordが空文字の場合nullになること(): void
    {
        $request = Request::create('/', 'GET', ['keyword' => '']);

        $dto = new SearchConditionDto($request);

        $this->assertNull($dto->keyword);
    }

    /**
     * @testWith [0]
     *           [1]
     */
    public function test_construct_statusに有効なEnumの値が設定されること(int $value): void
    {
        $request = Request::create('/', 'GET', ['status' => (string) $value]);

        $dto = new SearchConditionDto($request);

        $this->assertSame(UserStatus::from($value), $dto->status);
    }

    public function test_construct_statusが空文字の場合nullになること(): void
    {
        $request = Request::create('/', 'GET', ['status' => '']);

        $dto = new SearchConditionDto($request);

        $this->assertNull($dto->status);
    }

    public function test_construct_has_googleが1の場合trueになること(): void
    {
        $request = Request::create('/', 'GET', ['has_google' => '1']);

        $dto = new SearchConditionDto($request);

        $this->assertTrue($dto->hasGoogle);
    }

    public function test_construct_has_googleが0の場合falseになること(): void
    {
        $request = Request::create('/', 'GET', ['has_google' => '0']);

        $dto = new SearchConditionDto($request);

        $this->assertFalse($dto->hasGoogle);
    }

    public function test_construct_has_googleが空文字の場合nullになること(): void
    {
        $request = Request::create('/', 'GET', ['has_google' => '']);

        $dto = new SearchConditionDto($request);

        $this->assertNull($dto->hasGoogle);
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

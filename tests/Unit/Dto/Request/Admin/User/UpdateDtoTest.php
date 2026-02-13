<?php

namespace Tests\Unit\Dto\Request\Admin\User;

use App\Dto\Request\Admin\User\UpdateDto;
use App\Http\Requests\Admin\User\UpdateRequest;
use Tests\BaseTest;

class UpdateDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = UpdateRequest::create('/', 'POST', [
            'name'  => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        $dto = new UpdateDto($request);

        $this->assertSame('テストユーザー', $dto->name);
        $this->assertSame('test@example.com', $dto->email);
    }

    public function test_construct_値が空文字の場合空文字が設定されること(): void
    {
        $request = UpdateRequest::create('/', 'POST', [
            'name'  => '',
            'email' => '',
        ]);

        $dto = new UpdateDto($request);

        $this->assertSame('', $dto->name);
        $this->assertSame('', $dto->email);
    }
}

<?php

namespace Tests\Unit\Dto\Response\Api\Profile;

use App\Dto\Response\Api\Profile\UpdateDto;
use Tests\BaseTest;

class UpdateDtoTest extends BaseTest
{
    public function test_construct_プロパティが正しく設定されること(): void
    {
        $dto = new UpdateDto('テストユーザー', 'https://example.com/avatar.png');

        $this->assertSame('テストユーザー', $dto->name);
        $this->assertSame('https://example.com/avatar.png', $dto->avatarUrl);
    }

    public function test_construct_avatarUrlがnullの場合(): void
    {
        $dto = new UpdateDto('テストユーザー', null);

        $this->assertSame('テストユーザー', $dto->name);
        $this->assertNull($dto->avatarUrl);
    }
}

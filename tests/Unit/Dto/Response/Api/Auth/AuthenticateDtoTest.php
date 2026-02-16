<?php

namespace Tests\Unit\Dto\Response\Api\Auth;

use App\Dto\Response\Api\Auth\AuthenticateDto;
use Tests\BaseTest;

class AuthenticateDtoTest extends BaseTest
{
    public function test_construct_プロパティが正しく設定されること(): void
    {
        $dto = new AuthenticateDto('テストユーザー', 'https://example.com/avatar.png');

        $this->assertSame('テストユーザー', $dto->name);
        $this->assertSame('https://example.com/avatar.png', $dto->avatarUrl);
    }

    public function test_construct_avatarUrlがnullの場合(): void
    {
        $dto = new AuthenticateDto('テストユーザー', null);

        $this->assertSame('テストユーザー', $dto->name);
        $this->assertNull($dto->avatarUrl);
    }
}

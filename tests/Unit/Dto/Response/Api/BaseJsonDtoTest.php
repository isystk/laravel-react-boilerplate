<?php

namespace Tests\Unit\Dto\Response\Api;

use App\Dto\Response\Api\BaseJsonDto;
use Tests\BaseTest;

class BaseJsonDtoTest extends BaseTest
{
    public function test_construct_resultがtrueで初期化されること(): void
    {
        $dto = new BaseJsonDto(true);

        $this->assertTrue($dto->result);
        $this->assertSame('', $dto->message);
    }

    public function test_construct_resultがfalseで初期化されること(): void
    {
        $dto = new BaseJsonDto(false);

        $this->assertFalse($dto->result);
        $this->assertSame('', $dto->message);
    }

    public function test_messageを後から設定できること(): void
    {
        $dto          = new BaseJsonDto(true);
        $dto->message = '処理が完了しました';

        $this->assertSame('処理が完了しました', $dto->message);
    }
}

<?php

namespace Tests\Unit\Dto\Response\Api\Like;

use App\Dto\Response\Api\Like\SearchResultDto;
use Tests\BaseTest;

class SearchResultDtoTest extends BaseTest
{
    public function test_construct_stockIdsが正しく設定されること(): void
    {
        $stockIds = [1, 2, 3];

        $dto = new SearchResultDto($stockIds);

        $this->assertSame([1, 2, 3], $dto->stockIds);
    }

    public function test_construct_BaseJsonDtoのresultがtrueであること(): void
    {
        $dto = new SearchResultDto([]);

        $this->assertTrue($dto->result);
        $this->assertSame('', $dto->message);
    }

    public function test_construct_空の配列で初期化できること(): void
    {
        $dto = new SearchResultDto([]);

        $this->assertCount(0, $dto->stockIds);
    }
}

<?php

namespace Tests\Unit\Dto\Response\Api\Stock;

use App\Dto\Response\Api\Stock\SearchResultDto;
use App\Dto\Response\Api\Stock\StockDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class SearchResultDtoTest extends BaseTest
{
    use RefreshDatabase;

    public function test_construct_プロパティが正しく設定されること(): void
    {
        $stock1    = $this->createDefaultStock();
        $stock2    = $this->createDefaultStock();
        $stockDtos = [
            new StockDto($stock1),
            new StockDto($stock2),
        ];

        $dto = new SearchResultDto($stockDtos, 1, 100);

        $this->assertCount(2, $dto->stocks);
        $this->assertSame(1, $dto->currentPage);
        $this->assertSame(100, $dto->total);
    }

    public function test_construct_BaseJsonDtoのresultがtrueであること(): void
    {
        $dto = new SearchResultDto([], 1, 0);

        $this->assertTrue($dto->result);
        $this->assertSame('', $dto->message);
    }

    public function test_construct_空のstocks配列で初期化できること(): void
    {
        $dto = new SearchResultDto([], 1, 0);

        $this->assertCount(0, $dto->stocks);
        $this->assertSame(1, $dto->currentPage);
        $this->assertSame(0, $dto->total);
    }
}

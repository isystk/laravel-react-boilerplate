<?php

namespace Tests\Unit\Dto\Response\Api\Cart;

use App\Dto\Response\Api\Cart\CartStockDto;
use App\Dto\Response\Api\Cart\SearchResultDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class SearchResultDtoTest extends BaseTest
{
    use RefreshDatabase;

    public function test_construct_プロパティが正しく設定されること(): void
    {
        $cart   = $this->createDefaultCart();
        $stock1 = $this->createDefaultStock();
        $stock2 = $this->createDefaultStock();

        $stocks = [
            new CartStockDto($cart, $stock1),
            new CartStockDto($cart, $stock2),
        ];

        $dto = new SearchResultDto($stocks, 'user@example.com', 5000, 3);

        $this->assertCount(2, $dto->stocks);
        $this->assertSame('user@example.com', $dto->email);
        $this->assertSame(5000, $dto->sum);
        $this->assertSame(3, $dto->count);
    }

    public function test_construct_BaseJsonDtoのresultがtrueであること(): void
    {
        $dto = new SearchResultDto([], 'user@example.com', 0, 0);

        $this->assertTrue($dto->result);
        $this->assertSame('', $dto->message);
    }

    public function test_construct_空のstocks配列で初期化できること(): void
    {
        $dto = new SearchResultDto([], 'user@example.com', 0, 0);

        $this->assertCount(0, $dto->stocks);
        $this->assertSame(0, $dto->sum);
        $this->assertSame(0, $dto->count);
    }
}

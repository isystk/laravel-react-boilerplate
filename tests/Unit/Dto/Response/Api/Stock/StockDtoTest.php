<?php

namespace Tests\Unit\Dto\Response\Api\Stock;

use App\Dto\Response\Api\Stock\StockDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class StockDtoTest extends BaseTest
{
    use RefreshDatabase;

    public function test_construct_Stockモデルからプロパティが正しく設定されること(): void
    {
        $stock = $this->createDefaultStock([
            'name'            => 'テスト商品',
            'price'           => 1500,
            'quantity'        => 10,
            'image_file_name' => 'test.jpg',
        ]);

        $dto = new StockDto($stock);

        $this->assertSame($stock->id, $dto->id);
        $this->assertSame('テスト商品', $dto->name);
        $this->assertSame('http://localhost/uploads/stock/test.jpg', $dto->imageUrl);
        $this->assertSame(1500, $dto->price);
        $this->assertSame(10, $dto->quantity);
    }
}

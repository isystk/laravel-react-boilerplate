<?php

namespace Tests\Unit\Dto\Response\Api\Cart;

use App\Dto\Response\Api\Cart\CartStockDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class CartStockDtoTest extends BaseTest
{
    use RefreshDatabase;

    public function test_construct_CartとStockモデルからプロパティが正しく設定されること(): void
    {
        $cart = $this->createDefaultCart();

        $stock = $this->createDefaultStock([
            'name'            => 'テスト商品',
            'price'           => 2000,
            'image_file_name' => 'test.jpg',
        ]);

        $dto = new CartStockDto($cart, $stock);

        $this->assertSame($cart->id, $dto->id);
        $this->assertSame($stock->id, $dto->stockId);
        $this->assertSame('テスト商品', $dto->name);
        $this->assertSame('http://localhost/uploads/stock/test.jpg', $dto->imageUrl);
        $this->assertSame(2000, $dto->price);
    }
}

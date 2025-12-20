<?php

namespace App\Dto\Response\Api\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Entities\Stock;

class CartStockDto
{
    // カートID
    public int $id;

    // 商品ID
    public int $stockId;

    // 商品名
    public string $name;

    // 商品画像URL
    public string $imageUrl;

    // 価格
    public int $price;

    public function __construct(
        Cart $cart,
        Stock $stock,
    ) {
        $this->id = $cart->id;
        $this->stockId = $stock->id;
        $this->name = $stock->name;
        $this->imageUrl = $stock->getImageUrl();
        $this->price = $stock->price;
    }
}

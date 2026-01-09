<?php

namespace App\Dto\Response\Api\Stock;

use App\Domain\Entities\Stock;

class StockDto
{
    // 商品ID
    public int $id;

    // 商品名
    public string $name;

    // 商品画像URL
    public string $imageUrl;

    // 価格
    public int $price;

    // 在庫数
    public int $quantity;

    public function __construct(
        Stock $stock
    ) {
        $this->id       = $stock->id;
        $this->name     = $stock->name;
        $this->imageUrl = $stock->getImageUrl();
        $this->price    = $stock->price;
        $this->quantity = $stock->quantity;
    }
}

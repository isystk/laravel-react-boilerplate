<?php

namespace App\Dto\Response\Api\OrderHistory;

use App\Domain\Entities\Stock;

class StockDto
{
    // 商品ID
    public int $id;

    // 商品名
    public string $name;

    // 商品画像URL
    public string $imageUrl;

    public function __construct(
        Stock $stock
    ) {
        $this->id       = $stock->id;
        $this->name     = $stock->name;
        $this->imageUrl = $stock->getImageUrl();
    }
}

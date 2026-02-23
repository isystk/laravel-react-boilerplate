<?php

namespace App\Dto\Response\Api\OrderHistory;

use App\Domain\Entities\Order;
use App\Domain\Entities\OrderStock;

class OrderDto
{
    /** @var int 注文ID */
    public int $id;

    /** @var int 合計金額 */
    public int $sumPrice;

    /** @var string 注文日時 */
    public string $createdAt;

    /** @var array<array{stock: StockDto, price: int, quantity: int}> 注文商品リスト */
    public array $items;

    /**
     * @param array<OrderStock> $orderStocks
     */
    public function __construct(Order $order, array $orderStocks)
    {
        $this->id        = $order->id;
        $this->sumPrice  = $order->sum_price;
        $this->createdAt = $order->created_at->toDateTimeString();
        $this->items     = array_map(fn (OrderStock $orderStock) => [
            'stock'    => new StockDto($orderStock->stock),
            'price'    => $orderStock->price,
            'quantity' => $orderStock->quantity,
        ], $orderStocks);
    }
}

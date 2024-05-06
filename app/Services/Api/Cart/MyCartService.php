<?php

namespace App\Services\Api\Cart;

use App\Domain\Repositories\Cart\CartRepository;
use App\Domain\Repositories\Order\OrderRepository;
use App\Domain\Repositories\Order\OrderStockRepository;
use App\Domain\Repositories\Stock\StockRepository;
use Illuminate\Http\Request;

class MyCartService extends BaseCartService
{
    /**
     * @var StockRepository
     */
    protected StockRepository $stockRepository;

    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    public function __construct(
        Request $request,
        CartRepository $cartRepository,
        StockRepository $stockRepository,
        OrderRepository $orderRepository
    )
    {
        parent::__construct($request, $cartRepository);
        $this->stockRepository = $stockRepository;
        $this->orderRepository = $orderRepository;
    }

}

<?php

namespace App\Services\Api\Shop;

use App\Domain\Repositories\Order\OrderRepository;
use App\Domain\Repositories\Stock\StockRepository;
use Illuminate\Http\Request;

class MyCartService extends BaseShopService
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
        StockRepository $stockRepository,
        OrderRepository $orderRepository
    )
    {
        parent::__construct($request);
        $this->stockRepository = $stockRepository;
        $this->orderRepository = $orderRepository;
    }

}

<?php

namespace App\Services\Admin\Order;

use App\Domain\Repositories\Order\OrderRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class IndexService extends BaseService
{
    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    public function __construct(
        Request $request,
        OrderRepository $orderRepository
    )
    {
        parent::__construct($request);
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param int $limit
     * @return Collection|LengthAwarePaginator|array<string>
     */
    public function searchOrder(int $limit = 20): Collection|LengthAwarePaginator|array
    {
        return $this->orderRepository->findAll($this->request()->name, [
            'with:user' => true,
            'with:stock' => true,
            'limit' => $limit,
        ]);
    }

    /**
     * @param int $orderId
     * @return object|null
     */
    public function find(int $orderId): object|null
    {
        return $this->orderRepository->getById($orderId);
    }

}

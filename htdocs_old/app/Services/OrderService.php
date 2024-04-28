<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Repositories\OrderRepository;

class OrderService extends BaseService
{
    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    public function __construct(
        Request         $request,
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
    public function list(int $limit = 20): Collection|LengthAwarePaginator|array
    {
        return $this->orderRepository->findAll($this->request()->name, [
            'with:user' => true,
            'with:stock' => true,
            'limit' => $limit
        ]);
    }

    /**
     * @param string $orderId
     * @return object|null
     */
    public function find(string $orderId): object|null
    {
        return $this->orderRepository->find($orderId);
    }

}

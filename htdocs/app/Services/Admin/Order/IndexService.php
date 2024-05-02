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
     * @return Collection|LengthAwarePaginator|array<string>
     */
    public function searchOrder(): Collection|LengthAwarePaginator|array
    {
        $limit = 20;
        return $this->orderRepository->getConditionsWithUserStock([
            'user_name' => $this->request()->name,
            'limit' => $limit,
        ]);
    }

}

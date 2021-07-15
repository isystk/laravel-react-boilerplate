<?php

namespace App\Services;

use App\Constants\ErrorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;

class OrderService extends Service
{
  /**
   * @var OrderRepository
   */
  protected $orderRepository;

  public function __construct(
    Request $request,
    OrderRepository $orderRepository
) {
    parent::__construct($request);
    $this->orderRepository = $orderRepository;
  }

  public function list($limit = 20)
  {
    return $this->orderRepository->findAll($this->request()->name, [
      'with:user'=>true,
      'with:stock'=>true,
      'paging'=>$limit
    ]);
  }

  public function find($orderId)
  {
    return $this->orderRepository->findById($orderId, []);
  }

}

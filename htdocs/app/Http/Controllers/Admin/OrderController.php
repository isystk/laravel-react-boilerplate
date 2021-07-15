<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
  /**
   * @var OrderService
   */
  protected $orderService;

  public function __construct(OrderService $orderService)
  {
    $this->orderService = $orderService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $name = $request->name;
    $orders = $this->orderService->list();

    return view('admin.order.index', compact('orders', 'name'));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $order = $this->orderService->find($id);

    return view('admin.order.show', compact('order'));
  }
}

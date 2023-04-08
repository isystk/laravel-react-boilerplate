<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {

        $name = $request->name;
        $orders = $this->orderService->list();

        return view('admin.order.index', compact('orders', 'name'));
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return View
     */
    public function show(Order $order): View
    {
        return view('admin.order.show', compact('order'));
    }
}

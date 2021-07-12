<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\CSVService;
use App\Services\OrderService;

use Barryvdh\DomPDF\PDF;

class OrderController extends Controller
{
  /**
   * @var OrderService
   * @var PDF
   */
  protected $orderService;
  protected $pdfService;

  public function __construct(OrderService $orderService, PDF $pdfService)
  {
    $this->orderService = $orderService;
    $this->pdfService = $pdfService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $name = $request->input('name');

    $orders = $this->orderService->searchOrder($name, true);

    return view('admin.order.index', compact('orders', 'name'));
  }


  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function downloadCsv(Request $request)
  {

    $name = $request->input('name');

    $orders = $this->orderService->searchOrder($name, false);

    $csvHeader = ['ID', '注文者', '商品名', '個数', '発注日時'];
    $csvBody = [];
    foreach ($orders as $order) {
      $line = [];
      $line[] = $order->id;
      $line[] = $order->user_name;
      $line[] = $order->stock_name;
      $line[] = $order->quantity;
      $line[] = $order->created_at;
      $csvBody[] = $line;
    }
    return CSVService::download($csvBody, $csvHeader, 'orders.csv');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function downloadPdf(Request $request)
  {

    $name = $request->input('name');

    $orders = $this->orderService->searchOrder($name, false);

    $csvHeader = ['ID', '注文者', '商品名', '個数', '発注日時'];
    $csvBody = [];
    foreach ($orders as $order) {
      $line = [];
      $line[] = $order->id;
      $line[] = $order->user_name;
      $line[] = $order->stock_name;
      $line[] = $order->quantity;
      $line[] = $order->created_at;
      $csvBody[] = $line;
    }

    $pdf = $this->pdfService->loadView('admin.order.pdf', compact('csvHeader', 'csvBody'));
    return $pdf->download('orders.pdf');
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
    $order = Order::find($id);

    return view('admin.order.show', compact('order'));
  }
}

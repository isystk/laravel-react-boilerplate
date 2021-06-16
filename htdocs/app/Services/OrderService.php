<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class OrderService
{
  public function __construct()
  {
  }

  public function searchOrder($name, $hasPaging)
  {

    // 検索フォーム
    $query = DB::table('orders')
      ->join('users', 'users.id', '=', 'orders.user_id')
      ->join('stocks', 'stocks.id', '=', 'orders.stock_id');

    // もしキーワードがあったら
    if ($name !== null) {
      $query->where('users.name', 'like', '%' . $name . '%');
    }

    $query->select('orders.id', 'users.name as user_name', 'stocks.name as stock_name', 'orders.quantity', 'orders.created_at');
    $query->orderBy('orders.created_at', 'desc');
    $query->orderBy('orders.id', 'desc');
    if ($hasPaging) {
      $orders = $query->paginate(20);
    } else {
      $orders = $query->get();
    }

    // dd($orders);
    return $orders;
  }
}

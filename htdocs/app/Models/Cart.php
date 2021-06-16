<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
  protected $fillable = [
    'stock_id', 'user_id',
  ];

  public function showCart()
  {
    $user_id = Auth::id();
    $data['data'] = $this->where('user_id', $user_id)->get();

    $data['count'] = 0;
    $data['sum'] = 0;

    foreach ($data['data'] as $my_cart) {
      $data['count']++;
      $data['sum'] += $my_cart->stock->price;
    }
    return $data;
  }

  public function stock()
  {
    return $this->belongsTo('\App\Models\Stock');
  }

  public function addCart($stock_id)
  {
    $user_id = Auth::id();
    $cart_add_info = Cart::firstOrCreate(['stock_id' => $stock_id, 'user_id' => $user_id]);

    if ($cart_add_info->wasRecentlyCreated) {
      $message = 'カートに追加しました';
    } else {
      $message = 'カートに登録済みです';
    }

    return $message;
  }

  public function deleteCart($stock_id)
  {
    $user_id = Auth::id();
    $delete = $this->where('user_id', $user_id)->where('stock_id', $stock_id)->delete();

    if ($delete > 0) {
      $message = 'カートから選択した商品を削除しました';
    } else {
      $message = '削除に失敗しました';
    }
    return $message;
  }

  public function deleteMyCart()
  {
    $user_id = Auth::id();
    $delete = $this->where('user_id', $user_id)->delete();

    if ($delete > 0) {
      $message = 'カートから選択した商品を削除しました';
    } else {
      $message = '削除に失敗しました';
    }
    return $message;
  }

  public function checkoutCart()
  {
    // ユーザーのカートを取得する。
    $user_id = Auth::id();
    $checkout_items = $this->where('user_id', $user_id)->get();

    // カートを空にする。
    $this->where('user_id', $user_id)->delete();

    return $checkout_items;
  }
}

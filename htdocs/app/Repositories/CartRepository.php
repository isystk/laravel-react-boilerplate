<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepository
{

  public function count($userId, $options = [])
  {
      return Cart::where([
        'user_id' => $userId,
      ])->count();
  }

  public function findAll($userId, $options = [])
  {
      $query = Cart::with($this->__with($options))
        ->where([
          'user_id' => $userId,
        ]);

      $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
      return $limit > 0 ? $query->paginate($limit) : $query->get();
  }

  public function findById($id, $options = [])
  {
      return Cart::with($this->__with($options))
          ->where([
              'id' => $id
          ])
          ->first();
  }

  private function __with($options = [])
  {
      $with = [];
      if (!empty($options['with:stock'])) {
          $with[] = 'stock';
      }
      return $with;
  }

  public function store(
      $id,
      $stockId,
      $userId
  ) {
      $cart = new Cart();
      $cart->id = $id;
      $cart->stock_id = $stockId;
      $cart->user_id = $userId;

      $cart->save();

      return $cart;
  }

  public function update(
    $id,
    $stockId,
    $userId
  ) {
      $cart = $this->findById($id);
      $cart->stock_id = $stockId;
      $cart->user_id = $userId;
      $cart->save();

      return $cart;
  }

  public function delete(
    $id
  ) {
      $cart = $this->findById($id);
      $cart->delete();

      return $cart;
  }

}

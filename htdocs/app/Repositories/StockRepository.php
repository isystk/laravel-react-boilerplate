<?php

namespace App\Repositories;

use App\Models\Stock;

class StockRepository
{

  public function count($userId, $stock, $options = [])
  {
      return Stock::where([
        'user_id' => $userId,
        'stock_id' => $stock,
      ])->count();
  }

  public function findAll($userId, $stock, $options = [])
  {
      return Stock::with($this->__with($options))
          ->where([
            'user_id' => $userId,
            'stock_id' => $stock,
          ])
          ->get();
  }

  public function findById($id, $options = [])
  {
      return Stock::with($this->__with($options))
          ->where([
              'id' => $id
          ])
          ->first();
  }

  private function __with($options = [])
  {
      $with = [];
      if (!empty($options['with:orders'])) {
          $with[] = 'orders';
      }
      return $with;
  }

  public function store(
      $id,
      $name,
      $detail,
      $price,
      $imgpath
  ) {
      $stock = new Stock();
      $stock->id = $id;
      $stock->name = $name;
      $stock->detail = $detail;
      $stock->price = $price;
      $stock->imgpath = $imgpath;

      $stock->save();

      return $stock;
  }

  public function update(
    $id,
    $name,
    $detail,
    $price,
    $imgpath
  ) {
      $stock = $this->findById($id);
      $stock->name = $name;
      $stock->detail = $detail;
      $stock->price = $price;
      $stock->imgpath = $imgpath;
      $stock->save();

      return $stock;
  }

  public function delete(
    $id
  ) {
      $stock = $this->findById($id);
      $stock->delete();

      return $stock;
  }

}

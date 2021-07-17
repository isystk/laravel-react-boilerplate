<?php

namespace App\Repositories;

use App\Models\Stock;

class StockRepository
{

  public function count($name, $options = [])
  {
      return Stock::where('name', 'like', '%' . $name . '%')->count();
  }

  public function findAll($name, $options = [])
  {
      $query = Stock::with($this->__with($options))
        ->where('name', 'like', '%' . $name . '%')
        ->orderBy('created_at', 'desc')
        ->orderBy('id', 'asc');

      $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
      return $limit > 0 ? $query->paginate($limit) : $query->get();
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
      $quantity,
      $imgpath
  ) {
      $stock = new Stock();
      $stock->id = $id;
      $stock->name = $name;
      $stock->detail = $detail;
      $stock->price = $price;
      $stock->quantity = $quantity;
      $stock->imgpath = $imgpath;

      $stock->save();

      return $stock;
  }

  public function update(
    $id,
    $name,
    $detail,
    $price,
    $quantity,
    $imgpath
  ) {
      $stock = $this->findById($id);
      $stock->name = $name;
      $stock->detail = $detail;
      $stock->price = $price;
      $stock->quantity = $quantity;
      if (!empty($imgpath)) {
        $stock->imgpath = $imgpath;
      }
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

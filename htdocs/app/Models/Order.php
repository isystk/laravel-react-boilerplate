<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $guarded = [
    'id'
  ];
  // 親テーブル
  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }
  // 親テーブル
  public function stock()
  {
    return $this->belongsTo('App\Models\Stock');
  }
}

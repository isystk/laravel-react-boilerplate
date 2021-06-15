<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
  protected $guarded = [
    'id'
  ];
  // 子テーブル
  public function orders()
  {
    return $this->hasMany('App\Models\Order');
  }
}

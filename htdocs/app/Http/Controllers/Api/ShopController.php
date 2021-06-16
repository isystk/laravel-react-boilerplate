<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Cart;

use MyCartService;

class ShopController extends ApiController
{
  public function index()
  {
    try {
      $stocks = Stock::Paginate(6); //Eloquantで検索
      $result = [
        'result'      => true,
        'stocks'     => $stocks
      ];
    } catch (\Exception $e) {
      $result = [
        'result' => false,
        'error' => [
          'messages' => [$e->getMessage()]
        ],
      ];
      return $this->resConversionJson($result, $e->getCode());
    }
    return $this->resConversionJson($result);
  }

  public function myCart(Cart $cart)
  {

    try {
      $carts = MyCartService::searchMyCart($cart);
      $result = [
        'result'      => true,
        'carts'     => $carts
      ];
    } catch (\Exception $e) {
      $result = [
        'result' => false,
        'error' => [
          'messages' => [$e->getMessage()]
        ],
      ];
      return $this->resConversionJson($result, $e->getCode());
    }
    return $this->resConversionJson($result);
  }

  public function addMycart(Request $request, Cart $cart)
  {
    try {
      //カートに追加の処理
      $message = MyCartService::addMyCart($cart, $request->stock_id);

      //追加後の情報を取得
      $carts = MyCartService::searchMyCart($cart);

      $result = [
        'result'      => true,
        'message'     => $message,
        'carts'     => $carts
      ];
    } catch (\Exception $e) {
      $result = [
        'result' => false,
        'error' => [
          'messages' => [$e->getMessage()]
        ],
      ];
      return $this->resConversionJson($result, $e->getCode());
    }
    return $this->resConversionJson($result);
  }

  public function deleteCart(Request $request, Cart $cart)
  {
    try {
      //カートから削除の処理
      $message = MyCartService::deleteMyCart($cart, $request->stock_id);

      //追加後の情報を取得
      $carts = MyCartService::searchMyCart($cart);

      $result = [
        'result'      => true,
        'message'     => $message,
        'carts'     =>  $carts
      ];
    } catch (\Exception $e) {
      $result = [
        'result' => false,
        'error' => [
          'messages' => [$e->getMessage()]
        ],
      ];
      return $this->resConversionJson($result, $e->getCode());
    }
    return $this->resConversionJson($result);
  }

  public function createPayment(Request $request)
  {
    try {

      $result = MyCartService::createPayment($request);

    } catch (\Exception $e) {
      $result = [
        'result' => false,
        'error' => [
          'messages' => [$e->getMessage()]
        ],
      ];
      return $this->resConversionJson($result, $e->getCode());
    }
    return $this->resConversionJson($result);
  }

  public function checkout(Request $request, Cart $cart)
  {
    try {

      // 支払い処理の実行
      MyCartService::checkout($request, $cart);

      // 削除後の情報を取得
      $carts = MyCartService::searchMyCart($cart);

      $result = [
        'result' => true,
        'carts' => $carts
      ];
    } catch (\Exception $e) {
      $result = [
        'result' => false,
        'error' => [
          'messages' => [$e->getMessage()]
        ],
      ];
      return $this->resConversionJson($result, $e->getCode());
    }
    return $this->resConversionJson($result);
  }
}

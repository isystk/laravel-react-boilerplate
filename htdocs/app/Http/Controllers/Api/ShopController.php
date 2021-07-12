<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Cart;

use App\Services\MyCartService;

class ShopController extends ApiController
{
  /**
   * @var MyCartService
   */
  protected $myCartService;

  public function __construct(MyCartService $myCartService)
  {
      $this->myCartService = $myCartService;
  }

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
      $carts = $this->myCartService->searchMyCart($cart);
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
      $message = $this->myCartService->addMyCart($cart, $request->stock_id);

      //追加後の情報を取得
      $carts = $this->myCartService->searchMyCart($cart);

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
      $message = $this->myCartService->deleteMyCart($cart, $request->stock_id);

      //追加後の情報を取得
      $carts = $this->myCartService->searchMyCart($cart);

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

      $result = $this->myCartService->createPayment($request);

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
      $this->myCartService->checkout($request, $cart);

      // 削除後の情報を取得
      $carts = $this->myCartService->searchMyCart($cart);

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

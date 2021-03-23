<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Stock;

use App\Models\Cart;

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
            $carts = $cart->showCart();
            $datas = $carts['data'] ->map(function($cart, $key){
              $data = [];
              $data['id'] = $cart -> stock -> id;
              $data['name'] = $cart -> stock -> name;
              $data['price'] = $cart -> stock -> price;
              $data['imgpath'] = $cart -> stock -> imgpath;
              return $data;
            });

            $result = [
                'result'      => true,
                'carts'     => [
                  'data' => $datas,
                  'message' => 'test',
                  'total' => count($carts['data'])
                ]
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
          $stock_id = $request->stock_id;
          $message = $cart->addCart($stock_id);

          //追加後の情報を取得
          $carts = $cart->showCart();
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
          $stock_id = $request->stock_id;
          $message = $cart->deleteCart($stock_id);

          //追加後の情報を取得
          $carts = $cart->showCart();
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

}

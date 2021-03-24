<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Order;
use App\Models\Cart;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

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
                  'count' => $carts['count'],
                  'sum' => $carts['sum']
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

    public function createPayment(Request $request) {
      try {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $result = $stripe->paymentIntents->create([
          'amount' => $request -> amount,
          'currency' => 'jpy',
          'description'=> 'LaraEC',
          'metadata'=> [
            'username'=> $request -> username
          ]
        ]);
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

          // Stripe::setApiKey(env('STRIPE_SECRET'));

          // // 料金を支払う人
          // $customer = Customer::create(array(
          //     'email' => $request->stripeEmail,
          //     'source' => $request->stripeToken
          // ));

          $data = $cart->showCart();

          // // 料金の支払いを実行
          // $charge = Charge::create(array(
          //     'customer' => $customer->id,
          //     'amount' => $data['sum'],
          //     'currency' => 'jpy'
          // ));

          // 発注履歴に追加する。
          foreach ($data['data'] as $my_cart) {
              $stock = Stock::find($my_cart->stock_id);

              $order = new Order;
              $order->stock_id = $my_cart->stock_id;
              $order->user_id = $my_cart->user_id;
              $order->price =  $stock->price;
              $order->quantity = 1;
              $order->save();

              // 在庫を減らす
              $stock->quantity = $stock->quantity - $order->quantity;
              $stock->save();
          }

          // カートからすべての商品を削除
          $cart->deleteMyCart();

          $result = [
            'result' => true,
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

<?php

namespace App\Services;

use App\Enums\ErrorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Cart;
use App\Mail\MailNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class MyCartService extends Service
{
  public function __construct(
    Request $request
) {
    parent::__construct($request);
  }


  public function searchMyCart(Cart $cart)
  {
    return $this->convertToMycart($cart);
  }

  private function convertToMycart(Cart $cart)
  {
    $carts = $cart->showCart();
    $datas = $carts['data']->map(function ($cart, $key) {
      $data = [];
      $data['id'] = $cart->stock->id;
      $data['name'] = $cart->stock->name;
      $data['price'] = $cart->stock->price;
      $data['imgpath'] = $cart->stock->imgpath;
      return $data;
    });

    return [
      'data' => $datas,
      'username' => Auth::user()->email,
      'count' => $carts['count'],
      'sum' => $carts['sum']
    ];
  }

  public function addMyCart($cart, $stock_id)
  {
    //カートに追加の処理
    return $cart->addCart($stock_id);
  }

  public function deleteMyCart($cart, $stock_id)
  {
    //カートから削除の処理
    return $cart->deleteCart($stock_id);
  }

  public function createPayment($request)
  {
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

    $result = $stripe->paymentIntents->create([
      'amount' => $request->amount,
      'currency' => 'jpy',
      'description' => 'LaraEC',
      'metadata' => [
        'username' => $request->username
      ]
    ]);

    return $result;
  }

  public function checkout($request, $cart)
  {
    // Stripe::setApiKey(env('STRIPE_SECRET'));

    // // 料金を支払う人
    // $customer = Customer::create(array(
    //   'email' => $request->stripeEmail,
    //   'source' => $request->stripeToken
    // ));

    $data = $cart->showCart();

    DB::beginTransaction();
    try {    //

      // // 料金の支払いを実行
      // $charge = Charge::create(array(
      //   'customer' => $customer->id,
      //   'amount' => $data['sum'],
      //   'currency' => 'jpy'
      // ));

      $stocks = [];

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

        array_push($stocks, (object) [
          'name' => $stock->name,
          'quantity' => $order->quantity,
          'price' => $order->price,
        ]);
      }

      $user = Auth::user();

      $mailData = (object) [
        'name' => $user->name,
        'amount' => $data['sum'],
        'stocks' => $stocks,
      ];

      // メール送信
      Mail::to($user->email)
        ->send(new MailNotification('stock_complete', '商品の購入が完了しました', $mailData));

      // カートからすべての商品を削除
      $cart->deleteMyCart();

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
      throw new \Exception($e);
    }
  }
}

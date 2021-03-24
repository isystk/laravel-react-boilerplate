<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;

use App\Models\Cart;
use App\Models\Order;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class ShopController extends Controller
{
    public function checkout(Request $request, Cart $cart)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // 料金を支払う人
            $customer = Customer::create(array(
                'email' => $request->stripeEmail,
                'source' => $request->stripeToken
            ));

            $data = $cart->showCart();

            // 料金の支払いを実行
            $charge = Charge::create(array(
                'customer' => $customer->id,
                'amount' => $data['sum'],
                'currency' => 'jpy'
            ));

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

            // ユーザーのカートを取得する。
            $checkout_info = $cart->checkoutCart();

            // 購入完了画面を表示
            return redirect('/complete');
        } catch (\Exception $ex) {
            // $ex->getMessage();

            // // 元の画面に戻る
            // $data = $cart->showCart();

            // return view('mycart', $data)->with('message', "決算処理に失敗しました。" . $ex);
            return redirect('/mycart');
        }

    }

}

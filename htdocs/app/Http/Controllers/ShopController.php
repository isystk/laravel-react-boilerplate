<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;

use App\Models\Cart;
use App\Models\Order;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class ShopController extends Controller
{
    public function index()
    {
        $stocks = Stock::Paginate(6); //Eloquantで検索
        return view('shop', compact('stocks'));
    }

    public function myCart(Cart $cart)
    {
        $data = $cart->showCart();
        return view('mycart', $data);
    }

    public function addMycart(Request $request, Cart $cart)
    {

        //カートに追加の処理
        $stock_id = $request->stock_id;
        $message = $cart->addCart($stock_id);

        //追加後の情報を取得
        $data = $cart->showCart();

        return view('mycart', $data)->with('message', $message); //追記
    }

    public function deleteCart(Request $request, Cart $cart)
    {

        //カートから削除の処理
        $stock_id = $request->stock_id;
        $message = $cart->deleteCart($stock_id);

        //追加後の情報を取得
        $data = $cart->showCart();

        return view('mycart', $data)->with('message', $message); //追記
    }

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
            foreach ($data['my_carts'] as $my_cart) {
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
            $ex->getMessage();

            // 元の画面に戻る
            $data = $cart->showCart();

            return view('mycart', $data)->with('message', "決算処理に失敗しました。" . $ex);
        }
    }

    public function complete()
    {
        // 購入完了画面を表示
        return view('checkout');
    }
}

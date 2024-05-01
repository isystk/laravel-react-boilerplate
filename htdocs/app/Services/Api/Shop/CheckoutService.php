<?php

namespace App\Services\Api\Shop;

use App\Domain\Entities\Cart;
use App\Domain\Repositories\Order\OrderRepository;
use App\Domain\Repositories\Stock\StockRepository;
use App\Mail\MailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckoutService extends BaseShopService
{
    /**
     * @var StockRepository
     */
    protected StockRepository $stockRepository;

    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    public function __construct(
        Request $request,
        StockRepository $stockRepository,
        OrderRepository $orderRepository
    )
    {
        parent::__construct($request);
        $this->stockRepository = $stockRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Request $request
     * @param Cart $cart
     * @throws \Exception
     */
    public function checkout(Request $request, Cart $cart): void
    {
        // Stripe::setApiKey(env('STRIPE_SECRET'));

        // // 料金を支払う人
        // $customer = Customer::create(array(
        //   'email' => $request->stripeEmail,
        //   'source' => $request->stripeToken
        // ));

        $data = $cart->showCart();

        // // 料金の支払いを実行
        // $charge = Charge::create(array(
        //   'customer' => $customer->id,
        //   'amount' => $data['sum'],
        //   'currency' => 'jpy'
        // ));

        $stocks = [];

        // 発注履歴に追加する。
        foreach ($data['data'] as $my_cart) {
            $stock = $this->stockRepository->getById($my_cart->stock_id);

            $order = $this->orderRepository->create([
                'stock_id' => $my_cart->stock_id,
                'user_id' => $my_cart->user_id,
                'price' => $stock->price,
                'quantity' => 1,
            ]);

            // 在庫を減らす
            $quantity = $stock->quantity - $order->quantity;
            $this->stockRepository->update(
                $stock->id,
                [
                    'quantity' => $quantity,
                ]
            );

            array_push($stocks, (object)[
                'name' => $stock->name,
                'quantity' => $order->quantity,
                'price' => $order->price,
            ]);
        }

        $user = Auth::user();

        $mailData = (object)[
            'name' => $user->name,
            'amount' => $data['sum'],
            'stocks' => $stocks,
        ];

        // メール送信
        Mail::to($user->email)
            ->send(new MailNotification('stock_complete', '商品の購入が完了しました', $mailData));

        // カートからすべての商品を削除
        $cart->deleteMyCart();
    }
}

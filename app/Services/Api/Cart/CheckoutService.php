<?php

namespace App\Services\Api\Cart;

use App\Domain\Repositories\Cart\CartRepository;
use App\Domain\Repositories\Order\OrderRepository;
use App\Domain\Repositories\Order\OrderStockRepository;
use App\Domain\Repositories\Stock\StockRepository;
use App\Mail\MailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckoutService extends BaseCartService
{

    private CartRepository $cartRepository;
    protected StockRepository $stockRepository;
    protected OrderRepository $orderRepository;
    protected OrderStockRepository $orderStockRepository;

    /**
     * Create a new controller instance.
     *
     * @param CartRepository $cartRepository
     * @param StockRepository $stockRepository
     * @param OrderRepository $orderRepository
     * @param OrderStockRepository $orderStockRepository
     */
    public function __construct(
        CartRepository $cartRepository,
        StockRepository $stockRepository,
        OrderRepository $orderRepository,
        OrderStockRepository $orderStockRepository,
    )
    {
        parent::__construct($cartRepository);
        $this->cartRepository = $cartRepository;
        $this->stockRepository = $stockRepository;
        $this->orderRepository = $orderRepository;
        $this->orderStockRepository = $orderStockRepository;
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function checkout(Request $request): void
    {
        // Stripe::setApiKey(env('STRIPE_SECRET'));

        // // 料金を支払う人
        // $customer = Customer::create(array(
        //   'email' => $request->stripeEmail,
        //   'source' => $request->stripeToken
        // ));
        $userId = Auth::id();
        $items = $this->getMyCart();

        // // 料金の支払いを実行
        // $charge = Charge::create(array(
        //   'customer' => $customer->id,
        //   'amount' => $data['sum'],
        //   'currency' => 'jpy'
        // ));

        $order = $this->orderRepository->create([
            'user_id' => $userId,
            'sum_price' => $items['sum'],
        ]);

        // 発注履歴に追加する。
        $stocks = [];
        foreach ($items['data'] as $data) {
            $orderStock = $this->orderStockRepository->create([
                'order_id' => $order->id,
                'stock_id' => $data['stock_id'],
                'price' => $data['price'],
                'quantity' => 1, // TODO 商品毎に個数をサマリーしたい
            ]);

            // 在庫を減らす
            $quantity = $data['quantity'] - $order->quantity;
            $this->stockRepository->update(
                $data['stock_id'],
                [
                    'quantity' => $quantity,
                ]
            );

            $stocks[] = (object)[
                'name' => $data['name'],
                'quantity' => $orderStock->quantity,
                'price' => $orderStock->price,
            ];
        }

        $user = Auth::user();

        $mailData = (object)[
            'name' => $user->name,
            'amount' => $items['sum'],
            'stocks' => $stocks,
        ];

        // メール送信
        Mail::to($user->email)
            ->send(new MailNotification('stock_complete', '商品の購入が完了しました', $mailData));

        // カートからすべての商品を削除
        $userId = Auth::id();
        $this->cartRepository->deleteByUserId($userId);
    }
}

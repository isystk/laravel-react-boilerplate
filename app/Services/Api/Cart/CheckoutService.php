<?php

namespace App\Services\Api\Cart;

use App\Domain\Repositories\Cart\CartRepository;
use App\Domain\Repositories\Order\OrderRepository;
use App\Domain\Repositories\Order\OrderStockRepository;
use App\Domain\Repositories\Stock\StockRepository;
use App\Mails\CheckoutCompleteToUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckoutService extends BaseCartService
{
    private CartRepository $cartRepository;

    private StockRepository $stockRepository;

    private OrderRepository $orderRepository;

    private OrderStockRepository $orderStockRepository;

    public function __construct(
        CartRepository $cartRepository,
        StockRepository $stockRepository,
        OrderRepository $orderRepository,
        OrderStockRepository $orderStockRepository,
    ) {
        parent::__construct($cartRepository);
        $this->cartRepository = $cartRepository;
        $this->stockRepository = $stockRepository;
        $this->orderRepository = $orderRepository;
        $this->orderStockRepository = $orderStockRepository;
    }

    /**
     * 決済処理を行います。
     */
    public function checkout(?string $stripeEmail, ?string $stripeToken): void
    {
        // TODO Stripe との通信処理は一旦コメントアウト
        //        Stripe::setApiKey(config('const.stripe.secret'));
        //
        //        // 料金を支払う人
        //        $customer = Customer::create(array(
        //            'email' => $stripeEmail,
        //            'source' => $stripeToken,
        //        ));
        $userId = Auth::id();
        $items = $this->getMyCart();

        // Stripe 料金の支払いを実行
        //        Charge::create(array(
        //            'customer' => $customer->id,
        //            'amount' => $items['sum'],
        //            'currency' => 'jpy',
        //        ));

        $order = $this->orderRepository->create([
            'user_id' => $userId,
            'sum_price' => $items['sum'],
        ]);

        // 発注履歴に追加する。
        $orderItems = [];
        foreach ($items['data'] as $data) {
            $stockId = $data['stock_id'];

            $orderStock = $this->orderStockRepository->create([
                'order_id' => $order->id,
                'stock_id' => $stockId,
                'price' => $data['price'],
                'quantity' => 1, // TODO 商品毎に個数をサマリーしたい
            ]);

            // 在庫を減らす
            $stock = $this->stockRepository->findById($stockId);
            $newQuantity = $stock->quantity - 1;
            $this->stockRepository->update(
                $stockId,
                [
                    'quantity' => $newQuantity,
                ]
            );

            $orderItems[] = [
                'name' => $data['name'] ?? '',
                'quantity' => (int) $orderStock->quantity,
                'price' => (int) $orderStock->price,
            ];
        }

        $user = Auth::user();

        Mail::to($user->email)
            ->send(new CheckoutCompleteToUser(
                $user,
                $items['sum'],
                $orderItems
            ));

        // カートからすべての商品を削除
        $userId = Auth::id();
        $this->cartRepository->deleteByUserId($userId);
    }
}

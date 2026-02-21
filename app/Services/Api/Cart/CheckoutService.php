<?php

namespace App\Services\Api\Cart;

use App\Domain\Entities\User;
use App\Domain\Repositories\Cart\CartRepositoryInterface;
use App\Domain\Repositories\Order\OrderRepositoryInterface;
use App\Domain\Repositories\Order\OrderStockRepositoryInterface;
use App\Domain\Repositories\Stock\StockRepositoryInterface;
use App\Enums\OperationLogType;
use App\Helpers\AuthHelper;
use App\Mails\CheckoutCompleteToUser;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Mail;

class CheckoutService extends BaseCartService
{
    private readonly CartRepositoryInterface $cartRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        private readonly StockRepositoryInterface $stockRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderStockRepositoryInterface $orderStockRepository,
        private readonly OperationLogService $operationLogService,
    ) {
        parent::__construct($cartRepository);
        $this->cartRepository = $cartRepository;
    }

    /**
     * 決済後の後処理を行います。
     */
    public function checkout(): void
    {
        /** @var User $user */
        $user = AuthHelper::frontLoginedUser();

        $cart = $this->getMyCart();

        $order = $this->orderRepository->create([
            'user_id'   => $user->id,
            'sum_price' => $cart->sum,
        ]);

        // 発注履歴に追加する。
        $orderItems = [];
        foreach ($cart->stocks as $cartStock) {
            $stockId = $cartStock->stockId;

            $orderStock = $this->orderStockRepository->create([
                'order_id' => $order->id,
                'stock_id' => $stockId,
                'price'    => $cartStock->price,
                'quantity' => 1, // TODO 商品毎に個数をサマリーしたい
            ]);

            // 在庫を減らす
            $stock       = $this->stockRepository->findById($stockId);
            $newQuantity = $stock->quantity - 1;
            $this->stockRepository->update([
                'quantity' => $newQuantity,
            ], $stockId);

            $orderItems[] = [
                'name'     => $cartStock->name,
                'quantity' => (int) $orderStock->quantity,
                'price'    => (int) $orderStock->price,
            ];
        }

        Mail::to($user->email)
            ->send(new CheckoutCompleteToUser(
                $user,
                $cart->sum,
                $orderItems
            ));

        // カートからすべての商品を削除
        $this->cartRepository->deleteByUserId($user->id);

        $this->operationLogService->logUserAction(
            $user->id,
            OperationLogType::UserCheckout,
            "商品を購入 (注文ID: {$order->id}, 合計: ¥" . number_format((int) $cart->sum) . ')',
            request()->ip()
        );
    }
}

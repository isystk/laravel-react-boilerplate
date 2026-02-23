<?php

namespace App\Services\Api\Cart;

use App\Domain\Entities\User;
use App\Domain\Repositories\Cart\CartRepositoryInterface;
use App\Enums\OperationLogType;
use App\Helpers\AuthHelper;
use App\Services\Common\OperationLogService;

class AddCartService extends BaseCartService
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly OperationLogService $operationLogService,
    ) {
        parent::__construct($cartRepository);
    }

    /**
     * カートに商品を追加します。
     */
    public function addMyCart(int $stockId): string
    {
        /** @var User $user */
        $user = AuthHelper::frontLoginedUser();

        $cart = $this->cartRepository->create([
            'stock_id' => $stockId,
            'user_id'  => $user->id,
        ]);

        $this->operationLogService->logUserAction(
            $user->id,
            OperationLogType::UserCartAdd,
            "商品をカートに追加 (カートID: {$cart->id})",
            request()->ip()
        );

        return 'カートに追加しました';
    }
}

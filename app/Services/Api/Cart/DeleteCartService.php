<?php

namespace App\Services\Api\Cart;

use App\Domain\Entities\User;
use App\Domain\Repositories\Cart\CartRepositoryInterface;
use App\Enums\OperationLogType;
use App\Helpers\AuthHelper;
use App\Services\Common\OperationLogService;

class DeleteCartService extends BaseCartService
{
    private readonly CartRepositoryInterface $cartRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        private readonly OperationLogService $operationLogService
    ) {
        parent::__construct($cartRepository);
        $this->cartRepository = $cartRepository;
    }

    /**
     * カートから商品を削除します。
     */
    public function deleteMyCart(int $cartId): string
    {
        /** @var User $user */
        $user = AuthHelper::frontLoginedUser();

        $this->cartRepository->delete($cartId);

        $this->operationLogService->logUserAction(
            $user->id,
            OperationLogType::UserCartDelete,
            "商品をカートから削除 (カートID: {$cartId})",
            request()->ip()
        );

        return 'カートから選択した商品を削除しました';
    }
}

<?php

namespace App\Services\Api\Cart;

use App\Domain\Repositories\Cart\CartRepositoryInterface;

class DeleteCartService extends BaseCartService
{
    private readonly CartRepositoryInterface $cartRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository,
    ) {
        parent::__construct($cartRepository);
        $this->cartRepository = $cartRepository;
    }

    /**
     * カートから商品を削除します。
     */
    public function deleteMyCart(int $cartId): string
    {
        $this->cartRepository->delete($cartId);

        return 'カートから選択した商品を削除しました';
    }
}

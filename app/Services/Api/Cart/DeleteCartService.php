<?php

namespace App\Services\Api\Cart;

use App\Domain\Repositories\Cart\CartRepository;

class DeleteCartService extends BaseCartService
{
    private CartRepository $cartRepository;

    public function __construct(
        CartRepository $cartRepository,
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

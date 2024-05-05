<?php

namespace App\Services\Api\Cart;

class DeleteCartService extends BaseCartService
{
    /**
     * カートから商品を削除します。
     * @param int $cartId
     * @return string
     */
    public function deleteMyCart(int $cartId): string
    {
        $this->cartRepository->delete($cartId);

        return 'カートから選択した商品を削除しました';
    }
}

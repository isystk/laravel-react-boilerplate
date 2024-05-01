<?php

namespace App\Services\Api\Shop;

use App\Domain\Entities\Cart;

class DeleteCartService extends BaseShopService
{
    /**
     * @param Cart $cart
     * @param string $stock_id
     * @return string
     */
    public function deleteMyCart(Cart $cart, string $stock_id): string
    {
        //カートから削除の処理
        return $cart->deleteCart($stock_id);
    }
}

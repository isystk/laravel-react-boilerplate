<?php

namespace App\Services\Api\Shop;

use App\Domain\Entities\Cart;

class AddCartService extends BaseShopService
{
    /**
     * @param Cart $cart
     * @param string $stock_id
     * @return string
     */
    public function addMyCart(Cart $cart, string $stock_id): string
    {
        //カートに追加の処理
        return $cart->addCart($stock_id);
    }
}

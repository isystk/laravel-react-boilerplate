<?php

namespace App\Services\Api\Shop;

use App\Domain\Entities\Cart;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;

class BaseShopService extends BaseService
{

    /**
     * @param Cart $cart
     * @return array<string>
     */
    public function searchMyCart(Cart $cart): array
    {
        return $this->convertToMycart($cart);
    }

    /**
     * @param Cart $cart
     * @return array<string, string>
     */
    private function convertToMycart(Cart $cart): array
    {
        $carts = $cart->showCart();
        $datas = $carts['data']->map(function ($cart, $key)
        {
            $data = [];
            $data['id'] = $cart->stock->id;
            $data['name'] = $cart->stock->name;
            $data['price'] = $cart->stock->price;
            $data['imgpath'] = $cart->stock->imgpath;
            return $data;
        });

        return [
            'data' => $datas,
            'username' => Auth::user()->email,
            'count' => $carts['count'],
            'sum' => $carts['sum'],
        ];
    }
}

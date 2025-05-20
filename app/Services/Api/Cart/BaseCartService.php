<?php

namespace App\Services\Api\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Entities\Stock;
use App\Domain\Repositories\Cart\CartRepository;
use App\Helpers\AuthHelper;
use App\Services\BaseService;

class BaseCartService extends BaseService
{
    private CartRepository $cartRepository;

    public function __construct(
        CartRepository $cartRepository,
    ) {
        $this->cartRepository = $cartRepository;
    }

    /**
     * カートに追加された商品を取得します。
     *
     * @return array{
     *     data: array{
     *         id: int,
     *         stock_id: int,
     *         name: string|null,
     *         price: int|null,
     *         quantity: int|null,
     *         imgpath: string,
     *     }[],
     *     username: string,
     *     sum: int,
     *     count: int,
     * }
     */
    public function getMyCart(): array
    {
        $user = AuthHelper::frontLoginedUser();
        $items = [
            'data' => [],
            'username' => $user->email,
            'sum' => 0,
            'count' => 0,
        ];

        $carts = $this->cartRepository->getByUserId($user->id);
        $items['data'] = $carts->map(function ($cart) {
            /** @var Cart $cart */
            /** @var Stock $stock */
            $stock = $cart->stock;

            return [
                'id' => $cart->id,
                'stock_id' => $stock->id,
                'name' => $stock->name,
                'price' => $stock->price,
                'quantity' => $stock->quantity,
                'imgpath' => $stock->imgpath,
            ];
        })->all();

        foreach ($items['data'] as $item) {
            $items['sum'] += $item['price'];
            $items['count']++;
        }

        return $items;
    }
}

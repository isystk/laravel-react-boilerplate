<?php

namespace App\Services\Api\Cart;

use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use App\Domain\Repositories\Cart\CartRepository;
use App\Dto\Response\Api\Cart\CartStockDto;
use App\Dto\Response\Api\Cart\SearchResultDto;
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
     */
    public function getMyCart(): SearchResultDto
    {
        /** @var User $user */
        $user = AuthHelper::frontLoginedUser();

        $carts = $this->cartRepository->getByUserId($user->id);
        $items = $carts->map(function ($cart) {
            /** @var Stock $stock */
            $stock = $cart->stock;

            return new CartStockDto($cart, $stock);
        })->all();

        $sum = 0;
        $count = 0;
        foreach ($items as $item) {
            $sum += $item->price;
            $count++;
        }

        return new SearchResultDto($items, $user->email, $sum, $count);
    }
}

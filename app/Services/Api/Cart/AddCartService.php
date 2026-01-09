<?php

namespace App\Services\Api\Cart;

use App\Domain\Repositories\Cart\CartRepository;
use App\Helpers\AuthHelper;

class AddCartService extends BaseCartService
{
    private readonly CartRepository $cartRepository;

    public function __construct(
        CartRepository $cartRepository,
    ) {
        parent::__construct($cartRepository);
        $this->cartRepository = $cartRepository;
    }

    /**
     * カートに商品を追加します。
     */
    public function addMyCart(int $stockId): string
    {
        $user = AuthHelper::frontLoginedUser();
        $this->cartRepository->create([
            'stock_id' => $stockId,
            'user_id'  => $user->id,
        ]);

        return 'カートに追加しました';
    }
}

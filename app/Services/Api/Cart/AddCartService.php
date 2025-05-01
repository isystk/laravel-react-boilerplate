<?php

namespace App\Services\Api\Cart;

use App\Domain\Repositories\Cart\CartRepository;
use Illuminate\Support\Facades\Auth;

class AddCartService extends BaseCartService
{
    private CartRepository $cartRepository;

    /**
     * Create a new controller instance.
     *
     * @param CartRepository $cartRepository
     */
    public function __construct(
        CartRepository $cartRepository,
    ) {
        parent::__construct($cartRepository);
        $this->cartRepository = $cartRepository;
    }

    /**
     * カートに商品を追加します。
     * @param int $stockId
     * @return string
     */
    public function addMyCart(int $stockId): string
    {
        $userId = Auth::id();
        $this->cartRepository->create([
            'stock_id' => $stockId,
            'user_id' => $userId,
        ]);

        return 'カートに追加しました';
    }
}

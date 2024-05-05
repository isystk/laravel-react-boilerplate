<?php

namespace App\Services\Api\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddCartService extends BaseCartService
{
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
            'user_id' => $userId
        ]);

        return 'カートに追加しました';
    }
}

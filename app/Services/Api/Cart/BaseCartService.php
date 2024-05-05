<?php

namespace App\Services\Api\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Repositories\Cart\CartRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BaseCartService extends BaseService
{

    /**
     * @var CartRepository
     */
    protected CartRepository $cartRepository;

    public function __construct(
        Request $request,
        CartRepository $cartRepository,
    )
    {
        parent::__construct($request);
        $this->cartRepository = $cartRepository;
    }

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
        $carts = $this->getMyCart();
        $datas = $carts['data']->map(function ($cart, $key)
        {
            $data = [];
            $data['id'] = $cart->id;
            $data['stock_id'] = $cart->stock->id;
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

    /**
     * @return array{
     *     data: Collection,
     *     count: int,
     *     sum: int
     * }
     */
    protected function getMyCart(): array
    {
        $userId = Auth::id();
        $data['data'] = $this->cartRepository->getByUserId($userId);

        $data['count'] = 0;
        $data['sum'] = 0;

        foreach ($data['data'] as $cart) {
            $data['count']++;
            $data['sum'] += $cart->stock->price;
        }
        return $data;
    }
}

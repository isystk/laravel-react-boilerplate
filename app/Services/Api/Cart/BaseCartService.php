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
        $items = $this->getMyCart();
        $datas = $items['carts']->map(function ($cart, $key)
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
            'count' => $items['sum_count'],
            'sum' => $items['sum_price'],
        ];
    }

    /**
     * @return array{
     *     carts: Collection<int, Cart>,
     *     sum_price: int,
     *     sum_count: int
     * }
     */
    protected function getMyCart(): array
    {
        $userId = Auth::id();
        $items = [];
        $items['carts'] = $this->cartRepository->getByUserId($userId);

        $items['sum_price'] = 0;
        $items['sum_count'] = 0;
        foreach ($items['carts'] as $cart) {
            $items['sum_price'] += $cart->stock->price;
            $items['sum_count']++;
        }
        return $items;
    }
}

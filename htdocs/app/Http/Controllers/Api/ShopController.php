<?php

namespace App\Http\Controllers\Api;

use App\Domain\Entities\Cart;
use App\Domain\Entities\Stock;
use App\Services\Api\Shop\AddCartService;
use App\Services\Api\Shop\CheckoutService;
use App\Services\Api\Shop\CreatePaymentService;
use App\Services\Api\Shop\DeleteCartService;
use App\Services\Api\Shop\MyCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopController extends BaseApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * 商品一覧のデータをJSONで返却します。
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $stocks = Stock::Paginate(6); // TODO Eloquantで検索
//            /** @var StockService $service */
//            $service = app(StockService::class);
//            $stocks = $service->list($cart);
            $result = [
                'result' => true,
                'stocks' => $stocks,
            ];
        } catch (\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()],
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }

    /**
     * マイカートのデータをJSONで返却します。
     * @param Cart $cart
     * @return JsonResponse
     */
    public function myCart(Cart $cart): JsonResponse
    {
        try {
            /** @var MyCartService $service */
            $service = app(MyCartService::class);
            $carts = $service->searchMyCart($cart);
            $result = [
                'result' => true,
                'carts' => $carts,
            ];
        } catch (\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()],
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }

    /**
     * マイカートに商品を追加します。
     * @param Request $request
     * @param Cart $cart
     * @return JsonResponse
     */
    public function addMycart(Request $request, Cart $cart): JsonResponse
    {
        try {
            /** @var AddCartService $service */
            $service = app(AddCartService::class);
            //カートに追加の処理
            $message = $service->addMyCart($cart, $request->stock_id);

            //追加後の情報を取得
            $carts = $service->searchMyCart($cart);

            $result = [
                'result' => true,
                'message' => $message,
                'carts' => $carts,
            ];
        } catch (\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()],
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }

    /**
     * マイカートから商品を削除します。
     * @param Request $request
     * @param Cart $cart
     * @return JsonResponse
     */
    public function deleteCart(Request $request, Cart $cart): JsonResponse
    {
        try {
            /** @var DeleteCartService $service */
            $service = app(DeleteCartService::class);
            //カートから削除の処理
            $message =$service->deleteMyCart($cart, $request->stock_id);

            //追加後の情報を取得
            $carts = $service->searchMyCart($cart);

            $result = [
                'result' => true,
                'message' => $message,
                'carts' => $carts,
            ];
        } catch (\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()],
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }

    /**
     * マイカートのデータを元にStripe決済用のPaymentを生成してJSONで返却します。
     * @param Request $request
     * @return JsonResponse
     */
    public function createPayment(Request $request): JsonResponse
    {
        try {
            /** @var CreatePaymentService $service */
            $service = app(CreatePaymentService::class);
            $result = $service->createPayment($request);
        } catch (\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()],
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }

    /**
     * マイカートのデータをStripeで決済処理します。
     * @param Request $request
     * @param Cart $cart
     * @return JsonResponse
     */
    public function checkout(Request $request, Cart $cart): JsonResponse
    {
        try {
            /** @var CheckoutService $service */
            $service = app(CheckoutService::class);
            // 支払い処理の実行
            $service->checkout($request, $cart);

            // 削除後の情報を取得
            $carts = $service->searchMyCart($cart);

            $result = [
                'result' => true,
                'carts' => $carts,
            ];
        } catch (\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()],
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }
}

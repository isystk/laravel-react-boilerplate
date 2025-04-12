<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\Cart\AddCartService;
use App\Services\Api\Cart\CheckoutService;
use App\Services\Api\Cart\CreatePaymentService;
use App\Services\Api\Cart\DeleteCartService;
use App\Services\Api\Cart\MyCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CartController extends BaseApiController
{

    /**
     * マイカートのデータをJSONで返却します。
     * @return JsonResponse
     */
    public function myCart(): JsonResponse
    {
        try {
            /** @var MyCartService $service */
            $service = app(MyCartService::class);
            $carts = $service->getMyCart();
            $result = [
                'result' => true,
                'carts' => $carts,
            ];
        } catch (Throwable $e) {
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
     * @return JsonResponse
     */
    public function addMycart(Request $request): JsonResponse
    {
        try {
            /** @var AddCartService $service */
            $service = app(AddCartService::class);
            //カートに追加の処理
            $message = $service->addMyCart($request->stock_id);

            //追加後の情報を取得
            $carts = $service->getMyCart();

            $result = [
                'result' => true,
                'message' => $message,
                'carts' => $carts,
            ];
        } catch (Throwable $e) {
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
     * @return JsonResponse
     */
    public function deleteCart(Request $request): JsonResponse
    {
        try {
            /** @var DeleteCartService $service */
            $service = app(DeleteCartService::class);
            //カートから削除の処理
            $message =$service->deleteMyCart($request->cart_id);

            //追加後の情報を取得
            $carts = $service->getMyCart();

            $result = [
                'result' => true,
                'message' => $message,
                'carts' => $carts,
            ];
        } catch (Throwable $e) {
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
        } catch (Throwable $e) {
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
     * @return JsonResponse
     */
    public function checkout(Request $request): JsonResponse
    {
        try {
            /** @var CheckoutService $service */
            $service = app(CheckoutService::class);
            // 支払い処理の実行
            $service->checkout($request->stripeEmail, $request->stripeToken);

            // 削除後の情報を取得
            $carts = $service->getMyCart();

            $result = [
                'result' => true,
                'carts' => $carts,
            ];
        } catch (Throwable $e) {
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

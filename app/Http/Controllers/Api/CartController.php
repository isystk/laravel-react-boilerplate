<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\Cart\AddCartService;
use App\Services\Api\Cart\CheckoutService;
use App\Services\Api\Cart\CreatePaymentService;
use App\Services\Api\Cart\DeleteCartService;
use App\Services\Api\Cart\MyCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CartController extends BaseApiController
{
    /**
     * マイカートのデータをJSONで返却します。
     */
    public function myCart(): JsonResponse
    {
        /** @var MyCartService $service */
        $service = app(MyCartService::class);
        try {
            $carts = $service->getMyCart();
            $result = [
                'result' => true,
                'carts' => $carts,
            ];
        } catch (Throwable $e) {
            return $this->getErrorJsonResponse($e);
        }

        return response()->json($result);
    }

    /**
     * マイカートに商品を追加します。
     */
    public function addMycart(Request $request): JsonResponse
    {
        /** @var AddCartService $service */
        $service = app(AddCartService::class);
        try {
            // カートに追加の処理
            $message = $service->addMyCart($request->stock_id);

            // 追加後の情報を取得
            $carts = $service->getMyCart();

            $result = [
                'result' => true,
                'message' => $message,
                'carts' => $carts,
            ];
        } catch (Throwable $e) {
            return $this->getErrorJsonResponse($e);
        }

        return response()->json($result);
    }

    /**
     * マイカートから商品を削除します。
     */
    public function deleteCart(Request $request): JsonResponse
    {
        /** @var DeleteCartService $service */
        $service = app(DeleteCartService::class);
        try {
            // カートから削除の処理
            $message = $service->deleteMyCart($request->cart_id);

            // 追加後の情報を取得
            $carts = $service->getMyCart();

            $result = [
                'result' => true,
                'message' => $message,
                'carts' => $carts,
            ];
        } catch (Throwable $e) {
            return $this->getErrorJsonResponse($e);
        }

        return response()->json($result);
    }

    /**
     * マイカートのデータを元にStripe決済用のPaymentを生成してJSONで返却します。
     */
    public function createPayment(Request $request): JsonResponse
    {
        /** @var CreatePaymentService $service */
        $service = app(CreatePaymentService::class);
        try {
            $result = $service->createPayment($request);
        } catch (Throwable $e) {
            return $this->getErrorJsonResponse($e);
        }

        return response()->json($result);
    }

    /**
     * マイカートのデータをStripeで決済処理します。
     *
     * @throws Throwable
     */
    public function checkout(Request $request): JsonResponse
    {
        /** @var CheckoutService $service */
        $service = app(CheckoutService::class);
        DB::beginTransaction();
        try {
            // 支払い処理の実行
            $service->checkout($request->stripeEmail, $request->stripeToken);

            // 削除後の情報を取得
            $carts = $service->getMyCart();

            $result = [
                'result' => true,
                'carts' => $carts,
            ];

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return $this->getErrorJsonResponse($e);
        }

        return response()->json($result);
    }
}

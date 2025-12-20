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
use InvalidArgumentException;
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
            $result = $service->getMyCart();
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
            $result = $service->getMyCart();
            $result->message = $message;
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
            $result = $service->getMyCart();
            $result->message = $message;
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
        $stripeEmail = is_string($request->stripeEmail) ? $request->stripeEmail : null;
        $stripeToken = is_string($request->stripeToken) ? $request->stripeToken : null;

        //        if (is_null($stripeEmail) || is_null($stripeToken)) {
        //            throw new InvalidArgumentException('stripeEmail is null.');
        //        }

        /** @var CheckoutService $service */
        $service = app(CheckoutService::class);
        DB::beginTransaction();
        try {
            // 支払い処理の実行
            $service->checkout($stripeEmail, $stripeToken);

            // 削除後の情報を取得
            $result = $service->getMyCart();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return $this->getErrorJsonResponse($e);
        }

        return response()->json($result);
    }
}

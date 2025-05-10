<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\Shop\IndexService;
use Illuminate\Http\JsonResponse;
use Throwable;

class ShopController extends BaseApiController
{

    /**
     * 商品一覧のデータをJSONで返却します。
     */
    public function index(): JsonResponse
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);
        try {
            $stocks = $service->searchStock();
            $result = [
                'result' => true,
                'stocks' => $stocks,
            ];
        } catch (Throwable $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()],
                ],
            ];
            return response()->json($result, $e->getCode());
        }
        return response()->json($result);
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\Shop\IndexService;
use Illuminate\Http\JsonResponse;
use Throwable;

class ShopController extends BaseApiController
{

    /**
     * 商品一覧のデータをJSONで返却します。
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            /** @var IndexService $service */
            $service = app(IndexService::class);
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
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }

}

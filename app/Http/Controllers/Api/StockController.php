<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\Stock\IndexService;
use Illuminate\Http\JsonResponse;
use Throwable;

class StockController extends BaseApiController
{
    /**
     * 商品一覧のデータをJSONで返却します。
     */
    public function index(): JsonResponse
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);
        try {
            $result = $service->searchStock();
        } catch (Throwable $e) {
            return $this->getErrorJsonResponse($e);
        }

        return response()->json($result);
    }
}

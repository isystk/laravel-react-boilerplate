<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\OrderHistory\IndexService;
use Illuminate\Http\JsonResponse;
use Throwable;

class OrderHistoryController extends BaseApiController
{
    public function __construct(private readonly IndexService $indexService) {}

    /**
     * 注文履歴データをJSONで返却します。
     */
    public function index(): JsonResponse
    {
        try {
            $result = $this->indexService->getOrderHistory();
        } catch (Throwable $e) {
            return $this->getErrorJsonResponse($e);
        }

        return response()->json($result);
    }
}

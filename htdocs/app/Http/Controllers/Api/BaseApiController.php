<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Stripe\PaymentIntent;

class BaseApiController extends BaseController
{
    /**
     * @param array<string, mixed>|PaymentIntent $result
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function resConversionJson(array|PaymentIntent $result, int $statusCode = 200): JsonResponse
    {
        if (empty($statusCode) || $statusCode < 100 || $statusCode >= 600) {
            $statusCode = 500;
        }
        return response()->json($result, $statusCode, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Stripe\PaymentIntent;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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

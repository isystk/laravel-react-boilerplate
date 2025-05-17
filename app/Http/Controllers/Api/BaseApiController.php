<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Throwable;

class BaseApiController extends BaseController
{
    /**
     * エラー用のJSONレスポンスを返す
     */
    protected function getErrorJsonResponse(Throwable $e): JsonResponse
    {
        $items = [
            'result' => false,
            'error' => [
                'messages' => [$e->getMessage()],
            ],
        ];

        return response()
            ->json($items, $e->getCode())
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}

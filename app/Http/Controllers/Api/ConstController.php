<?php

namespace App\Http\Controllers\Api;

use App\Enums\Age;
use App\Enums\Gender;
use Illuminate\Http\JsonResponse;
use Throwable;

class ConstController extends BaseApiController
{

    /**
     * 定数の一覧をJSONで返却します。
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $items = [];
        try {
            foreach ([
                'gender' => Gender::cases(),
                'age' => Age::cases(),
            ] as $key => $enum) {
                $items[$key] = [];
                foreach ($enum as $item) {
                    $items[$key][] = [
                        'key' => $item->value,
                        'value' => $item->label(),
                    ];
                }
            }
        } catch (Throwable $e) {
            return $this->getErrorJsonResponse($e);
        }
        return response()->json([
            'result' => true,
            'data' => $items,
        ]);
    }
}

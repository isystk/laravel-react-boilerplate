<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContactType;
use Illuminate\Http\JsonResponse;
use Throwable;

class ConstController extends BaseApiController
{
    /**
     * 定数の一覧をJSONで返却します。
     */
    public function index(): JsonResponse
    {
        $items = [];

        try {
            foreach ([
                'contactType' => ContactType::cases(),
            ] as $key => $enum) {
                $items[$key] = [];
                foreach ($enum as $item) {
                    $items[$key][] = [
                        'key'   => $item->value,
                        'value' => $item->label(),
                    ];
                }
            }
        } catch (Throwable $e) {
            return $this->getErrorJsonResponse($e);
        }

        return response()->json([
            'result' => true,
            'data'   => $items,
        ]);
    }
}

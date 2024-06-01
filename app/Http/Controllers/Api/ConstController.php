<?php

namespace App\Http\Controllers\Api;

use App\Utils\ConstUtil;
use Illuminate\Http\JsonResponse;

class ConstController extends BaseApiController
{

    /**
     * 定数の一覧をJSONで返却します。
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $consts = ConstUtil::searchConst();

            $result = [
                'result' => true,
                'consts' => [
                    'data' => $consts,
                ],
            ];
        } catch (\Exception $e) {
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

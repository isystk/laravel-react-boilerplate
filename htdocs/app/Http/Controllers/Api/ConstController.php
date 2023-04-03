<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Utils\ConstUtil;
use Illuminate\Http\JsonResponse;


class ConstController extends ApiController
{

    public function __construct()
    {
    }

    /**
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
                ]
            ];
        } catch (\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()]
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }
}

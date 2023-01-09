<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Services\Utils\ConstService;
use Illuminate\Http\JsonResponse;


class ConstController extends ApiController
{
    /**
     * @var ConstService
     */
    protected ConstService $constService;

    public function __construct(ConstService $constService)
    {
        $this->constService = $constService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        try {
            $consts = $this->constService->searchConst();

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

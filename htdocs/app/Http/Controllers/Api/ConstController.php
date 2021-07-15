<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Services\Utils\ConstService;


class ConstController extends ApiController
{
  /**
   * @var ConstService
   */
  protected $constService;

  public function __construct(ConstService $constService)
  {
      $this->constService = $constService;
  }

  public function index()
  {

    try {
      $consts = $this->constService->searchConst();

      $result = [
        'result'      => true,
        'consts'     =>  [
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

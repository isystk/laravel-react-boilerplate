<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Services\ConstService;

class ConstController extends ApiController
{
  public function index()
  {

    try {
      $consts = ConstService::searchConst();

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

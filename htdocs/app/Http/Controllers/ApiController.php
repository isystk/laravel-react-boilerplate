<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  protected function resConversionJson($result, $statusCode = 200)
  {
    if (empty($statusCode) || $statusCode < 100 || $statusCode >= 600) {
      $statusCode = 500;
    }
    return response()->json($result, $statusCode, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
  }
}

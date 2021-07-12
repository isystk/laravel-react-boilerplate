<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Services\CookieService;

class LikeController extends ApiController
{
  /**
   * @var CookieService
   */
  protected $cookieService;

  public function __construct(CookieService $cookieService)
  {
      $this->cookieService = $cookieService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    try {
      $likes = $this->cookieService->getLike();
      $result = [
        'result'      => true,
        'likes'     => [
          'data' => $likes
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

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    try {
      $stockId = $request->input('id');
      $this->cookieService->saveLike($stockId);
      $result = [
        'result' => true,
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

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    try {
      $this->cookieService->removeLike($id);
      $result = [
        'result' => true,
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

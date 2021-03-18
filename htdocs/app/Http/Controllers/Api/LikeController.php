<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Services\CookieUtil;

class LikeController extends ApiController
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    try {
      $likes = CookieUtil::getLike();
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
      CookieUtil::saveLike($stockId);
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
      CookieUtil::removeLike($id);
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

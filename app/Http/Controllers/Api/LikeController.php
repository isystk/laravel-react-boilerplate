<?php

namespace App\Http\Controllers\Api;

use App\Utils\CookieUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends BaseApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * お気に入りデータをJSONで返却します。
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $likes = CookieUtil::getLike();
            $result = [
                'result' => true,
                'likes' => [
                    'data' => $likes,
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

    /**
     * お気に入りに追加します。
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
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
                    'messages' => [$e->getMessage()],
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }

    /**
     * お気に入りから削除します。
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
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
                    'messages' => [$e->getMessage()],
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }
}
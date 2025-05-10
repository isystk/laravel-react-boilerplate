<?php

namespace App\Http\Controllers\Api;

use App\Dto\Response\Api\LikeJsonDto;
use App\Utils\CookieUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class LikeController extends BaseApiController
{

    /**
     * お気に入りデータをJSONで返却します。
     */
    public function index(): JsonResponse
    {
        try {
            $configCookieKey = config('const.cookie.like.key');
            $stringStockIds = CookieUtil::get($configCookieKey);
            $stockIds = array_map('intval', $stringStockIds);
            $result = new LikeJsonDto($stockIds);
        } catch (Throwable $e) {
            return $this->getErrorJsonResponse($e);
        }
        return response()->json($result);
    }

    /**
     * お気に入りに追加します。
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $stockId = $request->input('id');
            $configCookie = config('const.cookie.like');
            CookieUtil::add($configCookie['key'], $stockId, $configCookie['expire']);
        } catch (Throwable $e) {
            return $this->getErrorJsonResponse($e);
        }
        return response()->json(['result' => true]);
    }

    /**
     * お気に入りから削除します。
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $configCookieKey = config('const.cookie.like.key');
            CookieUtil::remove($configCookieKey, $id);
        } catch (Throwable $e) {
            return $this->getErrorJsonResponse($e);
        }
        return response()->json(['result' => true]);
    }
}

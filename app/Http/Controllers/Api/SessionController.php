<?php

namespace App\Http\Controllers\Api;

use App\Domain\Entities\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends BaseApiController
{
    /**
     * ログインユーザーで返却します。
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        return $this->getJsonResponse($user);
    }
}

<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class LogoutController extends BaseApiController
{
    /**
     * セッションを無効化してログアウトします。
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = Auth::user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user !== null) {
            Event::dispatch(new Logout('web', $user));
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}

<?php

namespace App\Http\Controllers\Front\Auth\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     */
    public function toResponse($request): Response
    {
        // APIリクエストの場合はJSONレスポンスにする例
        if ($request->wantsJson()) {
            return response()->json();
        }

        // それ以外は従来のリダイレクトなど
        return redirect()->intended(config('fortify.home'));
    }
}

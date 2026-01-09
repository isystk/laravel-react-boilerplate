<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessControlMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName();
        if (!$routeName) {
            return $next($request);
        }

        $config = config('access_control.permissions');
        $user   = $request->user();

        // 完全一致、またはワイルドカードでマッチする設定を探す
        $allowedRoles = null;
        foreach ($config as $pattern => $roles) {
            if (str($routeName)->is($pattern)) {
                $allowedRoles = $roles;
                break;
            }
        }

        // 設定がある場合のみ権限チェック（設定がないルートは誰でも通過）
        if ($allowedRoles !== null) {
            // ユーザーがログインしていない、または許可されたロールを持っていない場合
            if (!$user || !in_array($user->role, $allowedRoles, true)) {
                abort(403, 'この操作を行う権限がありません。');
            }
        }

        return $next($request);
    }
}

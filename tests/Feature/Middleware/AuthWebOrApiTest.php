<?php

namespace Middleware;

use App\Http\Middleware\AuthWebOrApi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\BaseTest;

class AuthWebOrApiTest extends BaseTest
{
    use RefreshDatabase;

    public function test_handle_webガードでログインしていればアクセス可能(): void
    {
        // 修正：Illuminate\Http\Request::create を使用
        $request    = Request::create('/test', 'GET');
        $middleware = new AuthWebOrApi;

        Auth::shouldReceive('guard')->with('web')->andReturnSelf();
        Auth::shouldReceive('check')->once()->andReturn(true);

        $response = $middleware->handle($request, fn () => response()->json(['message' => 'success']));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_handle_apiガードでログインしていればアクセス可能(): void
    {
        $request    = Request::create('/test', 'GET');
        $middleware = new AuthWebOrApi;

        Auth::shouldReceive('guard')->with('web')->andReturnSelf();
        Auth::shouldReceive('check')->once()->andReturn(false);
        Auth::shouldReceive('guard')->with('api')->andReturnSelf();
        Auth::shouldReceive('check')->once()->andReturn(true);

        $response = $middleware->handle($request, fn () => response()->json(['message' => 'success']));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_handle_未ログインの場合は401(): void
    {
        $request    = Request::create('/test', 'GET');
        $middleware = new AuthWebOrApi;

        Auth::shouldReceive('guard')->with('web')->andReturnSelf();
        Auth::shouldReceive('check')->once()->andReturn(false);
        Auth::shouldReceive('guard')->with('api')->andReturnSelf();
        Auth::shouldReceive('check')->once()->andReturn(false);

        $response = $middleware->handle($request, fn () => response()->json(['message' => 'success']));

        $this->assertEquals(401, $response->getStatusCode());
    }
}

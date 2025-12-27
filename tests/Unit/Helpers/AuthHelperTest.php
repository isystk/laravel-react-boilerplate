<?php

namespace Helpers;

use App\Domain\Entities\User;
use App\Helpers\AuthHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\BaseTest;

class AuthHelperTest extends BaseTest
{
    use RefreshDatabase;

    public function test_front_logined_user_デフォルトのガードでログインしている場合にユーザーを返すこと(): void
    {
        $user = $this->createDefaultUser();
        $this->actingAs($user);

        $result = AuthHelper::frontLoginedUser();

        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($user->id, $result->id);
    }

    public function test_front_logined_user_ap_iガードでログインしている場合にユーザーを返すこと(): void
    {
        $user = $this->createDefaultUser();

        // デフォルトは未ログイン、APIガードのみログイン状態を作る
        Auth::shouldReceive('user')->once()->andReturn(null);
        Auth::shouldReceive('guard')->with('api')->once()->andReturnSelf();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $result = AuthHelper::frontLoginedUser();

        $this->assertSame($user, $result);
    }

    public function test_front_logined_user_どちらもログインしていない場合はnullを返すこと(): void
    {
        // 全てのガードでログインしていない状態
        Auth::shouldReceive('user')->once()->andReturn(null);
        Auth::shouldReceive('guard')->with('api')->once()->andReturnSelf();
        Auth::shouldReceive('user')->once()->andReturn(null);

        $result = AuthHelper::frontLoginedUser();

        $this->assertNull($result);
    }

    public function test_front_logout_全てのガードでログアウトが呼ばれること(): void
    {
        // ログアウトメソッドがそれぞれのガードで呼ばれることを検証
        Auth::shouldReceive('logout')->once();
        Auth::shouldReceive('guard')->with('api')->once()->andReturnSelf();
        Auth::shouldReceive('logout')->once();

        AuthHelper::frontLogout();
        /** @phpstan-ignore-next-line */
        $this->assertTrue(true);
    }
}

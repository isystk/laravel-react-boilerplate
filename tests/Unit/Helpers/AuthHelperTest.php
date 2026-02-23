<?php

namespace Tests\Unit\Helpers;

use App\Domain\Entities\User;
use App\Helpers\AuthHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\BaseTest;

class AuthHelperTest extends BaseTest
{
    use RefreshDatabase;

    public function test_frontLoginedUser_デフォルトのガードでログインしている場合にユーザーを返すこと(): void
    {
        $user = $this->createDefaultUser();
        $this->actingAs($user);

        $result = AuthHelper::frontLoginedUser();

        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($user->id, $result->id);
    }

    public function test_frontLoginedUser_ログインしていない場合はnullを返すこと(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $result = AuthHelper::frontLoginedUser();

        $this->assertNull($result);
    }

    public function test_frontLogout_ログアウトが呼ばれること(): void
    {
        Auth::shouldReceive('logout')->once();

        AuthHelper::frontLogout();
        /** @phpstan-ignore-next-line */
        $this->assertTrue(true);
    }
}

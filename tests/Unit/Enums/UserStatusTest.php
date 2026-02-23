<?php

namespace Tests\Unit\Enums;

use App\Enums\UserStatus;
use Tests\BaseTest;

class UserStatusTest extends BaseTest
{
    public function test_isActive(): void
    {
        $sut = UserStatus::Active;
        $this->assertTrue($sut->isActive(), '有効の場合 → True');

        $sut = UserStatus::Suspended;
        $this->assertFalse($sut->isActive(), 'アカウント停止の場合 → False');
    }
}

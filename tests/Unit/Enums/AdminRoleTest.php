<?php

namespace Tests\Unit\Enums;

use App\Enums\AdminRole;
use Tests\BaseTest;

class AdminRoleTest extends BaseTest
{
    public function test_isHighManager(): void
    {
        $sut = AdminRole::Manager;
        $this->assertFalse($sut->isHighManager(), '管理者の場合 → False');

        $sut = AdminRole::HighManager;
        $this->assertTrue($sut->isHighManager(), 'システム管理者の場合 → True');
    }
}

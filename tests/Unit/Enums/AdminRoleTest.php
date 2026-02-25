<?php

namespace Tests\Unit\Enums;

use App\Enums\AdminRole;
use Tests\BaseTest;

class AdminRoleTest extends BaseTest
{
    public function test_isSuperAdmin(): void
    {
        $sut = AdminRole::Staff;
        $this->assertFalse($sut->isSuperAdmin(), '管理者の場合 → False');

        $sut = AdminRole::SuperAdmin;
        $this->assertTrue($sut->isSuperAdmin(), 'システム管理者の場合 → True');
    }
}

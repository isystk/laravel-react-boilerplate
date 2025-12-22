<?php

namespace Domain\Entities;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use Tests\BaseTest;

class AdminTest extends BaseTest
{
    private Admin $sub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new Admin;
    }

    public function test_is_high_manager(): void
    {
        $this->assertFalse($this->sub->isHighManager(), '上位管理者以外の場合 → False');
        $this->sub->role = AdminRole::HighManager;
        $this->assertTrue($this->sub->isHighManager(), '上位管理者の場合 → True');
    }

    public function test_is_manager(): void
    {
        $this->assertFalse($this->sub->isManager(), '管理者以外の場合 → False');
        $this->sub->role = AdminRole::Manager;
        $this->assertTrue($this->sub->isManager(), '管理者の場合 → True');
    }
}

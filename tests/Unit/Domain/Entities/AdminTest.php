<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\BaseTest;

class AdminTest extends BaseTest
{
    use RefreshDatabase;

    private Admin $sub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new Admin;
    }

    public function test_正しくキャストされる事(): void
    {
        $model = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager->value,
        ]);

        $this->assertInstanceOf(AdminRole::class, $model->role);
        $this->assertInstanceOf(Carbon::class, $model->created_at);
        $this->assertInstanceOf(Carbon::class, $model->updated_at);
    }

    public function test_isHighManager(): void
    {
        $this->assertFalse($this->sub->isHighManager(), '上位管理者以外の場合 → False');
        $this->sub->role = AdminRole::HighManager;
        $this->assertTrue($this->sub->isHighManager(), '上位管理者の場合 → True');
    }

    public function test_isManager(): void
    {
        $this->assertFalse($this->sub->isManager(), '管理者以外の場合 → False');
        $this->sub->role = AdminRole::Manager;
        $this->assertTrue($this->sub->isManager(), '管理者の場合 → True');
    }
}

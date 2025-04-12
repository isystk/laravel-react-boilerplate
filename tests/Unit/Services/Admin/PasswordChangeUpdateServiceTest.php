<?php

namespace Services\Admin;

use App\Domain\Entities\Admin;
use App\Services\Admin\PasswordChangeUpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordChangeUpdateServiceTest extends TestCase
{

    use RefreshDatabase;

    private PasswordChangeUpdateService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PasswordChangeUpdateService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(PasswordChangeUpdateService::class, $this->service);
    }

    /**
     * updateのテスト
     */
    public function testUpdate(): void
    {
        /** @var Admin $admin */
        $admin = Admin::factory()->create([
            'name' => 'aaa',
            'email' => 'aaa@test.com',
            'password' => Hash::make('password')
        ]);

        $newPassword = Hash::make('newPassword');
        // データが更新されるこ
        $this->service->update($admin->id, $newPassword);

        // データが更新されたことをテスト
        $this->assertDatabaseHas('admins', ['id' => $admin->id, 'password' => $newPassword]);
    }
}

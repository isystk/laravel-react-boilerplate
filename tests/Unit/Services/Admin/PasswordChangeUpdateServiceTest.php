<?php

namespace Tests\Unit\Services\Admin;

use App\Services\Admin\PasswordChangeUpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\BaseTest;

class PasswordChangeUpdateServiceTest extends BaseTest
{
    use RefreshDatabase;

    private PasswordChangeUpdateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PasswordChangeUpdateService::class);
    }

    public function test_update(): void
    {
        $admin = $this->createDefaultAdmin([
            'name'     => 'aaa',
            'email'    => 'aaa@test.com',
            'password' => Hash::make('password'),
        ]);

        $newPassword = Hash::make('newPassword');
        // データが更新されるこ
        $this->service->update($admin->id, $newPassword);

        // データが更新されたことをテスト
        $this->assertDatabaseHas('admins', ['id' => $admin->id, 'password' => $newPassword]);
    }
}

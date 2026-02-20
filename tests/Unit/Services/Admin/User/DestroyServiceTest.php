<?php

namespace Tests\Unit\Services\Admin\User;

use App\Services\Admin\User\DestroyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class DestroyServiceTest extends BaseTest
{
    use RefreshDatabase;

    private DestroyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DestroyService::class);
    }

    public function test_delete(): void
    {
        $user = $this->createDefaultUser();
        $this->service->delete($user->id);

        // データが削除されたことをテスト
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}

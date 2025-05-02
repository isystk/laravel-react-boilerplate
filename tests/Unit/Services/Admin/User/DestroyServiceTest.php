<?php

namespace Tests\Unit\Services\Admin\User;

use App\Domain\Entities\User;
use App\Services\Admin\User\DestroyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyServiceTest extends TestCase
{

    use RefreshDatabase;

    private DestroyService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DestroyService::class);
    }

    /**
     * deleteのテスト
     */
    public function testDelete(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->service->delete($user->id);

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}

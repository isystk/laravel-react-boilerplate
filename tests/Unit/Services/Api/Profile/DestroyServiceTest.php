<?php

namespace Tests\Unit\Services\Api\Profile;

use App\Services\Api\Profile\DestroyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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

    public function test_destroy_ユーザーに関連するすべてのデータが削除されること(): void
    {
        Storage::fake('s3');
        $user = $this->createDefaultUser();

        $image = $this->createDefaultImage();
        $user->update(['avatar_image_id' => $image->id]);
        $user->refresh();

        $order = $this->createDefaultOrder(['user_id' => $user->id]);
        $this->createDefaultOrderStock(['order_id' => $order->id]);

        $this->createDefaultCart(['user_id' => $user->id]);

        $this->service->destroy($user);

        // ユーザーが削除されていること
        $this->assertSoftDeleted('users', ['id' => $user->id]);

        // 注文と注文商品が削除されていること
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('order_stocks', ['order_id' => $order->id]);

        // カートが削除されていること
        $this->assertDatabaseMissing('carts', ['user_id' => $user->id]);
    }
}

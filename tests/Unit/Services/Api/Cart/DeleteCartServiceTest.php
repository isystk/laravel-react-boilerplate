<?php

namespace Tests\Unit\Services\Api\Cart;

use App\Services\Api\Cart\DeleteCartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCartServiceTest extends TestCase
{
    use RefreshDatabase;

    private DeleteCartService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DeleteCartService::class);
    }

    /**
     * deleteMyCartのテスト
     */
    public function test_get_my_cart(): void
    {
        $user1 = $this->createDefaultUser([
            'name' => 'aaa',
            'email' => 'aaa@test.com',
        ]);
        // ユーザをログイン状態にする
        $this->actingAs($user1);

        $stock1 = $this->createDefaultStock(['name' => 'stock1']);
        $stock2 = $this->createDefaultStock(['name' => 'stock2']);

        $cart1 = $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock1->id]);
        $cart2 = $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock2->id]);

        $this->service->deleteMyCart($cart1->id);

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('carts', ['id' => $cart1->id]);
        // データが削除されていないことをテスト
        $this->assertDatabaseHas('carts', ['id' => $cart2->id]);
    }
}

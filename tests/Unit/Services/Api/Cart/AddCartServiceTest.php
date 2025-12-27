<?php

namespace Tests\Unit\Services\Api\Cart;

use App\Domain\Entities\Cart;
use App\Services\Api\Cart\AddCartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class AddCartServiceTest extends BaseTest
{
    use RefreshDatabase;

    private AddCartService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AddCartService::class);
    }

    public function test_add_my_cart(): void
    {
        $user1 = $this->createDefaultUser([
            'name' => 'aaa',
            'email' => 'aaa@test.com',
        ]);
        // ユーザをログイン状態にする
        $this->actingAs($user1);

        $stock1 = $this->createDefaultStock(['name' => 'stock1']);
        $this->service->addMyCart($stock1->id);
        $stock2 = $this->createDefaultStock(['name' => 'stock2']);
        $this->service->addMyCart($stock2->id);

        $carts = Cart::where(['user_id' => $user1->id]);
        $cartIds = $carts->pluck('stock_id')->all();
        $this->assertSame([$stock1->id, $stock2->id], $cartIds);
    }
}

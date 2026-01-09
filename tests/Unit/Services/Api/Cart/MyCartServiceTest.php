<?php

namespace Tests\Unit\Services\Api\Cart;

use App\Services\Api\Cart\MyCartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class MyCartServiceTest extends BaseTest
{
    use RefreshDatabase;

    private MyCartService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(MyCartService::class);
    }

    public function test_getMyCart(): void
    {
        $user1 = $this->createDefaultUser([
            'name'  => 'aaa',
            'email' => 'aaa@test.com',
        ]);
        // ユーザをログイン状態にする
        $this->actingAs($user1);

        $dto = $this->service->getMyCart();
        $this->assertCount(0, $dto->stocks, 'カートに追加した商品がない状態でエラーにならないことを始めにテスト');

        $stock1 = $this->createDefaultStock(['name' => 'stock1', 'price' => 111]);
        $stock2 = $this->createDefaultStock(['name' => 'stock2', 'price' => 222]);

        $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock1->id]);
        $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock2->id]);

        $dto      = $this->service->getMyCart();
        $stockIds = collect($dto->stocks)->pluck('stockId')->all();
        $this->assertSame([$stock1->id, $stock2->id], $stockIds, 'カートに追加した商品をテスト');
        $this->assertSame(2, $dto->count, 'カートに追加した商品の総数をテスト');
        $this->assertSame(333, $dto->sum, 'カートに追加した商品の総価格をテスト');
    }
}

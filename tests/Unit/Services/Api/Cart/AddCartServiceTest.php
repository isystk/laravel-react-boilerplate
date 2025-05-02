<?php

namespace Tests\Unit\Services\Api\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use App\Services\Api\Cart\AddCartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddCartServiceTest extends TestCase
{

    use RefreshDatabase;

    private AddCartService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AddCartService::class);
    }

    /**
     * addMyCartのテスト
     */
    public function testGetMyCart(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create([
            'name' => 'aaa',
            'email' => 'aaa@test.com',
        ]);
        // ユーザをログイン状態にする
        $this->actingAs($user1);

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1'])->create();
        $this->service->addMyCart($stock1->id);
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2'])->create();
        $this->service->addMyCart($stock2->id);

        $carts = Cart::where(['user_id' => $user1->id]);
        $cartIds = $carts->pluck('stock_id')->all();
        $this->assertSame([$stock1->id, $stock2->id], $cartIds);
    }
}

<?php

namespace Tests\Unit\Services\Api\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use App\Services\Api\Cart\DeleteCartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCartServiceTest extends TestCase
{

    use RefreshDatabase;

    private DeleteCartService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DeleteCartService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(DeleteCartService::class, $this->service);
    }

    /**
     * deleteMyCartのテスト
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
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2'])->create();

        /** @var Cart $cart1 */
        $cart1 = Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock1->id])->create();
        /** @var Cart $cart2 */
        $cart2 = Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock2->id])->create();

        $this->service->deleteMyCart($cart1->id);

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('carts', ['id' => $cart1->id]);
        // データが削除されていないことをテスト
        $this->assertDatabaseHas('carts', ['id' => $cart2->id]);
    }
}

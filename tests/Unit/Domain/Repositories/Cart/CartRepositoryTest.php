<?php

namespace Domain\Repositories\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use App\Domain\Repositories\Cart\CartRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartRepositoryTest extends TestCase
{

    use RefreshDatabase;

    private CartRepository $repository;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(CartRepository::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(CartRepository::class, $this->repository);
    }

    /**
     * getByUserIdのテスト
     */
    public function testGetByUserId(): void
    {
        /** @var User $user1 */
        $user1 = User::factory(['name' => 'user1', 'email' => 'user1@test.com'])->create();
        /** @var User $user2 */
        $user2 = User::factory(['name' => 'user2', 'email' => 'user2@test.com'])->create();

        $carts = $this->repository->getByUserId($user1->id);
        $this->assertCount(0, $carts, 'データがない状態で正常に動作することを始めにテスト');

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1'])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2'])->create();

        /** @var Cart $fitCart1 */
        $fitCart1 = Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock1->id])->create();
        /** @var Cart $fitCart2 */
        $fitCart2 = Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock2->id])->create();
        /** @var Cart $unfitCart1 */
        $unfitCart1 = Cart::factory(['user_id' => $user2->id, 'stock_id' => $stock1->id])->create();
        $expectCartIds = [$fitCart1->id, $fitCart2->id];

        $carts = $this->repository->getByUserId($user1->id);
        $cartIds = $carts->pluck('id')->all();
        $this->assertSame($expectCartIds, $cartIds, '指定したユーザーのカートが取得されることをテスト');
    }

    /**
     * deleteByUserIdのテスト
     */
    public function testDeleteByUserId(): void
    {
        /** @var User $user1 */
        $user1 = User::factory(['name' => 'user1', 'email' => 'user1@test.com'])->create();
        /** @var User $user2 */
        $user2 = User::factory(['name' => 'user2', 'email' => 'user2@test.com'])->create();

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1'])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2'])->create();

        Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock1->id])->create();
        Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock2->id])->create();
        Cart::factory(['user_id' => $user2->id, 'stock_id' => $stock1->id])->create();

        $this->repository->deleteByUserId($user1->id);

        $carts = Cart::where('user_id', $user1->id)->get();
        $this->assertCount(0, $carts, '指定したユーザーのカートが削除されることをテスト');

        $carts = Cart::where('user_id', $user2->id)->get();
        $this->assertCount(1, $carts, '指定以外のユーザーのカートが削除されていないことをテスト');
    }
}

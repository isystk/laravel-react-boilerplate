<?php

namespace Domain\Repositories\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Repositories\Cart\CartRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class CartRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private CartRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(CartRepository::class);
    }

    public function test_get_by_user_id(): void
    {
        $user1 = $this->createDefaultUser(['name' => 'user1', 'email' => 'user1@test.com']);
        $user2 = $this->createDefaultUser(['name' => 'user2', 'email' => 'user2@test.com']);

        $carts = $this->repository->getByUserId($user1->id);
        $this->assertCount(0, $carts, 'データがない状態で正常に動作することを始めにテスト');

        $stock1 = $this->createDefaultStock(['name' => 'stock1']);
        $stock2 = $this->createDefaultStock(['name' => 'stock2']);

        $fitCart1 = $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock1->id]);
        $fitCart2 = $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock2->id]);
        $this->createDefaultCart(['user_id' => $user2->id, 'stock_id' => $stock1->id]);
        $expectCartIds = [$fitCart1->id, $fitCart2->id];

        $carts = $this->repository->getByUserId($user1->id);
        $cartIds = $carts->pluck('id')->all();
        $this->assertSame($expectCartIds, $cartIds, '指定したユーザーのカートが取得されることをテスト');
    }

    public function test_delete_by_user_id(): void
    {
        $user1 = $this->createDefaultUser(['name' => 'user1', 'email' => 'user1@test.com']);
        $user2 = $this->createDefaultUser(['name' => 'user2', 'email' => 'user2@test.com']);

        $stock1 = $this->createDefaultStock(['name' => 'stock1']);
        $stock2 = $this->createDefaultStock(['name' => 'stock2']);

        $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock1->id]);
        $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock2->id]);
        $this->createDefaultCart(['user_id' => $user2->id, 'stock_id' => $stock1->id]);

        $this->repository->deleteByUserId($user1->id);

        $carts = Cart::where('user_id', $user1->id)->get();
        $this->assertCount(0, $carts, '指定したユーザーのカートが削除されることをテスト');

        $carts = Cart::where('user_id', $user2->id)->get();
        $this->assertCount(1, $carts, '指定以外のユーザーのカートが削除されていないことをテスト');
    }
}

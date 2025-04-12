<?php

namespace Feature\Http\Controllers\Api;

use App\Domain\Entities\Cart;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class CartControllerTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * マイカート 取得APIのテスト
     */
    public function testMyCart(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@test.com'
        ]);
        $this->actingAs($user1);

        // 商品が1つも追加されていない場合
        $response = $this->post(route('api.shop.mycart'), []);
        $response->assertSuccessful();
        $response->assertJson([
            "result" => true,
            "carts" => [
                "data" => [],
                "username" => $user1->email,
                "sum" => 0,
                "count" => 0
            ]
        ]);

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1', 'price' => 111])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2', 'price' => 222])->create();

        /** @var Cart $cart1 */
        $cart1 = Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock1->id])->create();
        /** @var Cart $cart2 */
        $cart2 = Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock2->id])->create();

        $response = $this->post(route('api.shop.mycart'), []);
        $response->assertSuccessful();
        $response->assertJson([
            "result" => true,
            "carts" => [
                "data" => [
                    ['id' => $cart1->id, 'stock_id' => $stock1->id, 'name' => 'stock1', 'price' => 111],
                    ['id' => $cart2->id, 'stock_id' => $stock2->id, 'name' => 'stock2', 'price' => 222],
                ],
                "username" => $user1->email,
                "sum" => 333,
                "count" => 2
            ]
        ]);
    }

    /**
     * マイカート 追加APIのテスト
     */
    public function testAddCart(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@test.com'
        ]);
        $this->actingAs($user1);

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1', 'price' => 111])->create();
        $response = $this->post(route('api.shop.addcart'), ['stock_id' => $stock1->id]);
        $response->assertSuccessful();
        $this->assertDatabaseCount('carts', 1);

        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2', 'price' => 222])->create();
        $response = $this->post(route('api.shop.addcart'), ['stock_id' => $stock2->id]);
        $response->assertSuccessful();
        $this->assertDatabaseCount('carts', 2);
    }

    /**
     * マイカート 削除APIのテスト
     */
    public function testDeleteCart(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@test.com'
        ]);
        $this->actingAs($user1);

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1', 'price' => 111])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2', 'price' => 222])->create();

        /** @var Cart $cart1 */
        $cart1 = Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock1->id])->create();
        /** @var Cart $cart2 */
        $cart2 = Cart::factory(['user_id' => $user1->id, 'stock_id' => $stock2->id])->create();

        $response = $this->post(route('api.shop.delete'), ['cart_id' => $cart1->id]);
        $response->assertSuccessful();
        $this->assertDatabaseCount('carts', 1);

        $response = $this->post(route('api.shop.delete'), ['cart_id' => $cart2->id]);
        $response->assertSuccessful();
        $this->assertDatabaseCount('carts', 0);
    }

}

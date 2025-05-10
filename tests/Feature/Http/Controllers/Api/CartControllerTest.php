<?php

namespace Http\Controllers\Api;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * マイカート 取得APIのテスト
     */
    public function test_my_cart(): void
    {
        $user1 = $this->createDefaultUser([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ]);
        $this->actingAs($user1);

        // 商品が1つも追加されていない場合
        $response = $this->post(route('api.mycart'), []);
        $response->assertSuccessful();
        $response->assertJson([
            'result' => true,
            'carts' => [
                'data' => [],
                'username' => $user1->email,
                'sum' => 0,
                'count' => 0,
            ],
        ]);

        $stock1 = $this->createDefaultStock(['name' => 'stock1', 'price' => 111]);
        $stock2 = $this->createDefaultStock(['name' => 'stock2', 'price' => 222]);

        $cart1 = $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock1->id]);
        $cart2 = $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock2->id]);

        $response = $this->post(route('api.mycart'), []);
        $response->assertSuccessful();
        $response->assertJson([
            'result' => true,
            'carts' => [
                'data' => [
                    ['id' => $cart1->id, 'stock_id' => $stock1->id, 'name' => 'stock1', 'price' => 111],
                    ['id' => $cart2->id, 'stock_id' => $stock2->id, 'name' => 'stock2', 'price' => 222],
                ],
                'username' => $user1->email,
                'sum' => 333,
                'count' => 2,
            ],
        ]);
    }

    /**
     * マイカート 追加APIのテスト
     */
    public function test_add_cart(): void
    {
        $user1 = $this->createDefaultUser([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ]);
        $this->actingAs($user1);

        $stock1 = $this->createDefaultStock(['name' => 'stock1', 'price' => 111]);
        $response = $this->post(route('api.mycart.add'), ['stock_id' => $stock1->id]);
        $response->assertSuccessful();
        $this->assertDatabaseCount('carts', 1);

        $stock2 = $this->createDefaultStock(['name' => 'stock2', 'price' => 222]);
        $response = $this->post(route('api.mycart.add'), ['stock_id' => $stock2->id]);
        $response->assertSuccessful();
        $this->assertDatabaseCount('carts', 2);
    }

    /**
     * マイカート 削除APIのテスト
     */
    public function test_delete_cart(): void
    {
        $user1 = $this->createDefaultUser([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ]);
        $this->actingAs($user1);

        $stock1 = $this->createDefaultStock(['name' => 'stock1', 'price' => 111]);
        $stock2 = $this->createDefaultStock(['name' => 'stock2', 'price' => 222]);

        $cart1 = $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock1->id]);
        $cart2 = $this->createDefaultCart(['user_id' => $user1->id, 'stock_id' => $stock2->id]);

        $response = $this->post(route('api.mycart.delete'), ['cart_id' => $cart1->id]);
        $response->assertSuccessful();
        $this->assertDatabaseCount('carts', 1);

        $response = $this->post(route('api.mycart.delete'), ['cart_id' => $cart2->id]);
        $response->assertSuccessful();
        $this->assertDatabaseCount('carts', 0);
    }
}

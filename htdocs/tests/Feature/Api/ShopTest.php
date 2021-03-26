<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ShopTest extends TestCase
{
    use WithoutMiddleware; // use this trait

    /**
     * トップページが正しく表示されることの確認
     */
    public function testShopList()
    {
        $response = $this->get('/api/shops');
        // レスポンスの検証
        $response->assertSee('厳選した素材で作ったマカロンです。');
        $response
            ->assertOk()  # ステータスコードが 200
        ;
    }

    /**
     * ログイン後にマイカートに商品を追加できることの確認
     */
    public function testAddCart()
    {
        // ユーザー取得
        $user = User::find(1);

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        // 認証されていることを確認
        $this->assertTrue(Auth::check());

        $response = $this->post('/api/addMycart', [
          'stock_id' => 2
        ]);
        // レスポンスの検証
        $response
            ->assertOk()  # ステータスコードが 200
        ;
    }
    /**
     * ログイン後にマイカートが表示されることの確認
     */
    public function testMyCart()
    {
        // ユーザー取得
        $user = User::find(1);

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        // 認証されていることを確認
        $this->assertTrue(Auth::check());

        $response = $this->post('/api/mycart');
        // レスポンスの検証
        $response
            ->assertOk()  # ステータスコードが 200
            ->assertJsonFragment([ # レスポンスJSON に以下の値を含む
              'result' => true,
              "carts" => [
                "sum" => 10000,
                "username" => "test1@test.com",
                "count" => 1,
                "data"  => [[
                "id"=> 2,
                "name"=> "Bluetoothヘッドフォン",
                "price"=> 10000,
                "imgpath"=> "headphone.jpg"
                ]]
              ]
            ]);
    }
}

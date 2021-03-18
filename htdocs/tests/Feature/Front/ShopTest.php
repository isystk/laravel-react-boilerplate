<?php

namespace Tests\Feature\Front;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ShopTest extends TestCase
{
    use WithoutMiddleware; // use this trait

    // /**
    //  * トップページが正しく表示されることの確認
    //  */
    // public function testShopList()
    // {
    //     $response = $this->get('/');
    //     // レスポンスの検証
    //     $response->assertSee('厳選した素材で作ったマカロンです。');
    //     $response
    //         ->assertOk()  # ステータスコードが 200
    //     ;
    // }

    // /**
    //  * ログイン後にマイカートが表示されることの確認
    //  */
    // public function testMyCart()
    // {
    //     // ユーザー取得
    //     $user = User::find(1);

    //     // 認証済み、つまりログイン済みしたことにする
    //     $this->actingAs($user);

    //     // 認証されていることを確認
    //     $this->assertTrue(Auth::check());

    //     $response = $this->post('/mycart');
    //     // レスポンスの検証
    //     $response->assertSee('テスト1さんのカートの中身');
    //     $response
    //         ->assertOk()  # ステータスコードが 200
    //     ;
    // }
}

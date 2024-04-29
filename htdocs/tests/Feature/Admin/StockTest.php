<?php

namespace Tests\Feature\Admin;

use App\Domain\Entities\Admin;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class StockTest extends TestCase
{
    use WithoutMiddleware; // use this trait

    /**
     * ログイン後に商品一覧が表示されることの確認
     */
    public function testStockList(): void
    {
        // ユーザー取得
        $user = Admin::find(1);

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        // 認証されていることを確認
        $this->assertTrue(Auth::check());

        $response = $this->get('/admin/stock');
        // レスポンスの検証
        $response->assertSee('マカロン');
        $response
            ->assertOk()  # ステータスコードが 200
        ;
    }
}

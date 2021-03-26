<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class ContactFormTest extends TestCase
{

    /**
     * お問い合わせ登録画面から投稿が正常に出来ることを確認する
     */
    public function testContactStore()
    {
        $this->withoutMiddleware();

        $response = $this->post('/api/contact/store', [
            'your_name' => 'テストユーザー',
            'email' => 'test@test.com',
            'gender' => '1',
            'age' => '3',
            'title' => 'テスト投稿',
            'contact' => 'テストからの投稿です。',
            'url' => '',
            // 'imageBase64' => '',
            'caution' => '1',
        ]);
        // レスポンスの検証
        $response->assertOk()  # ステータスコードが 200
          ->assertJsonFragment([ # レスポンスJSON に以下の値を含む
            'result' => true
          ]);
    }
}

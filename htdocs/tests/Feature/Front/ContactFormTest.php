<?php

namespace Tests\Feature\Front;

use Tests\TestCase;

class ContactFormTest extends TestCase
{
    // /**
    //  * お問い合わせ登録画面が正しく表示されることを確認する
    //  */
    // public function testContact()
    // {
    //     $response = $this->get('/contact');
    //     // レスポンスの検証
    //     $response->assertSee('お問い合わせ登録');
    //     $response
    //         ->assertOk();  # ステータスコードが 200
    // }

    // /**
    //  * お問い合わせ登録画面から投稿が正常に出来ることを確認する
    //  */
    // public function testContactStore()
    // {
    //     $this->withoutMiddleware();

    //     $response = $this->post('/contact/store', [
    //         'your_name' => 'テストユーザー',
    //         'email' => 'test@test.com',
    //         'gender' => '1',
    //         'age' => '3',
    //         'title' => 'テスト投稿',
    //         'contact' => 'テストからの投稿です。',
    //         'url' => '',
    //         // 'imageBase64' => '',
    //         'caution' => '1',
    //     ]);
    //     // 登録処理が完了して、完了画面にリダイレクトすることを検証
    //     $response->assertRedirect('/contact/complete');
    // }
}

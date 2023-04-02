<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class LikeTest extends TestCase
{
  /**
   * お気に入り情報を取得した場合に、正しく返却されることを確認する。
   *
   * @return void
   */
  public function testApiLikes()
  {
    $cookie = ['like' => '1,3'];
    $response = $this->call(
      'get',
      '/api/likes',
      [],
      $cookie
    );
    // $response->dump();
    // レスポンスの検証
    $response->assertOk()  # ステータスコードが 200
      ->assertJsonFragment([ # レスポンスJSON に以下の値を含む
        'likes' => [
          'data' => ['1', '3']
        ],
        'result' => true
      ]);
  }

  /**
   * お気に入りを追加した場合に、正しく返却されることを確認する。
   *
   * @return void
   */
  public function testApiLikesStore()
  {
    $response = $this->post('/api/likes/store', [
      'id' => '1'
    ]);
    // $response->dump();
    // レスポンスの検証
    $response->assertOk()  # ステータスコードが 200
      ->assertJsonFragment([ # レスポンスJSON に以下の値を含む
        'result' => true,
      ]);
  }

  /**
   * お気に入りを削除した場合に、正しく返却されることを確認する。
   *
   * @return void
   */
  public function testApiLikesDestroy()
  {
    $cookie = ['like' => '1,3'];
    $response = $this->call(
      'post',
      '/api/likes/destroy/1',
      [],
      $cookie
    );
    // $response->dump();
    // レスポンスの検証
    $response->assertOk()  # ステータスコードが 200
      ->assertJsonFragment([ # レスポンスJSON に以下の値を含む
        'result' => true,
      ]);
  }
}

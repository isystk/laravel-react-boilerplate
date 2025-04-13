<?php

namespace Http\Controllers\Front\Auth;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class OAuthControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
//        $this->providerName = 'google';
    }

    /**
     * Googleの認証画面を表示できる
     */
    public function testShowGoogleOAuth(): void
    {
        $this->markTestSkipped('認証キーを設定しないと動かないので一旦スキップ');

//        // URLをコール
//        $this->get(route('socialOAuth', ['provider' => $this->providerName]))
//            ->assertStatus(200);
    }

    /**
     * Googleアカウントでユーザー登録できる
     */
    public function testRegistGoogleOAuth(): void
    {
        $this->markTestSkipped('認証キーを設定しないと動かないので一旦スキップ');

//        // URLをコール
//        $this->get(route('oauthCallback', ['provider' => $this->providerName]))
//            ->assertStatus(200);
    }
}

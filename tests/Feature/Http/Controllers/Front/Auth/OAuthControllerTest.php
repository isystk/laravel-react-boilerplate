<?php

namespace Http\Controllers\Front\Auth;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\BaseTest;

class OAuthControllerTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
        //        $this->providerName = 'google';
    }

    public function test_show_google_o_auth(): void
    {
        $this->markTestSkipped('認証キーを設定しないと動かないので一旦スキップ');

        //        // URLをコール
        //        $this->get(route('socialOAuth', ['provider' => $this->providerName]))
        //            ->assertStatus(200);
    }

    public function test_regist_google_o_auth(): void
    {
        $this->markTestSkipped('認証キーを設定しないと動かないので一旦スキップ');

        //        // URLをコール
        //        $this->get(route('oauthCallback', ['provider' => $this->providerName]))
        //            ->assertStatus(200);
    }
}

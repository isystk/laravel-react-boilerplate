<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_I_can_login_successfully()
    {
        $this->browse(function ($browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'sample@sample.com')
                    ->type('password', 'password')
                    ->press('ログイン')
                    ->screenshot('test_I_can_login_successfully')
                    ->assertSee('ようこそ！管理者A');
        });
    }
}

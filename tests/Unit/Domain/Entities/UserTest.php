<?php

namespace Domain\Entities;

use App\Domain\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\BaseTest;

class UserTest extends BaseTest
{
    use RefreshDatabase;

    private User $sub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new User();
    }

    public function test_is_email_verified(): void
    {
        $this->assertFalse($this->sub->isEmailVerified(), 'メールアドレスの認証が未だの場合 → False');
        $this->sub->email_verified_at = Carbon::now();
        $this->assertTrue($this->sub->isEmailVerified(), 'メールアドレスが認証済みの場合 → True');
    }
}

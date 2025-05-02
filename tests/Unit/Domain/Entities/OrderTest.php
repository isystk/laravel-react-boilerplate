<?php

namespace Domain\Entities;

use App\Domain\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user(): void
    {
        $cart = $this->createDefaultCart();

        $result = $cart->user;
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($cart->user->id, $result->id);
    }

}

<?php

namespace Domain\Entities;

use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class CartTest extends BaseTest
{
    use RefreshDatabase;

    public function test_user(): void
    {
        $cart = $this->createDefaultCart();

        $result = $cart->user;
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($cart->user->id, $result->id);
    }

    public function test_stock(): void
    {
        $cart = $this->createDefaultCart();

        $result = $cart->stock;
        $this->assertInstanceOf(Stock::class, $result);
        $this->assertSame($cart->stock->id, $result->id);
    }
}

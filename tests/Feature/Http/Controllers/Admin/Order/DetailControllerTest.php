<?php

namespace Tests\Feature\Http\Controllers\Admin\Order;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class DetailControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_show(): void
    {
        $admin = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin, 'admin');

        $user1 = $this->createDefaultUser(['name' => 'user1']);

        $stock1 = $this->createDefaultStock(['name' => '商品1']);
        $stock2 = $this->createDefaultStock(['name' => '商品2']);

        $order1 = $this->createDefaultOrder(['user_id' => $user1->id, 'created_at' => '2024-05-01']);
        $this->createDefaultOrderStock(['order_id' => $order1->id, 'stock_id' => $stock1->id]);
        $this->createDefaultOrderStock(['order_id' => $order1->id, 'stock_id' => $stock2->id]);

        $response = $this->get(route('admin.order.show', $order1));
        $response->assertSuccessful();
        $response->assertSee('user1');
        $response->assertSee('商品1');
        $response->assertSee('商品2');
    }
}

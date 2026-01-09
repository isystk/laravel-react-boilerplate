<?php

namespace Tests\Feature\Http\Controllers\Admin\Order;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ListControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_index(): void
    {
        $admin = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin, 'admin');

        $user1 = $this->createDefaultUser(['name' => 'user1']);
        $user2 = $this->createDefaultUser(['name' => 'user2']);

        $stock1 = $this->createDefaultStock(['name' => '商品1']);
        $stock2 = $this->createDefaultStock(['name' => '商品2']);
        $stock3 = $this->createDefaultStock(['name' => '商品3']);

        $order1 = $this->createDefaultOrder(['user_id' => $user1->id, 'created_at' => '2024-05-01']);
        $this->createDefaultOrderStock(['order_id' => $order1->id, 'stock_id' => $stock1->id]);
        $this->createDefaultOrderStock(['order_id' => $order1->id, 'stock_id' => $stock2->id]);
        $order2 = $this->createDefaultOrder(['user_id' => $user2->id, 'created_at' => '2024-06-01']);
        $this->createDefaultOrderStock(['order_id' => $order2->id, 'stock_id' => $stock3->id]);
        $response = $this->get(route('admin.order'));
        $response->assertSuccessful();
        $response->assertSeeInOrder(['user2', 'user1']);
    }
}

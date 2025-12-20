<?php

namespace Http\Controllers\Api;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * 商品の一覧APIの取得テスト
     */
    public function test_index(): void
    {
        $this->createDefaultStock(['name' => 'stock1']);
        $stock2 = $this->createDefaultStock(['name' => 'stock2']);
        $stock3 = $this->createDefaultStock(['name' => 'stock3']);
        $stock4 = $this->createDefaultStock(['name' => 'stock4']);
        $stock5 = $this->createDefaultStock(['name' => 'stock5']);
        $stock6 = $this->createDefaultStock(['name' => 'stock6']);
        $stock7 = $this->createDefaultStock(['name' => 'stock7']);

        $response = $this->get(route('api.stock'));
        $response->assertSuccessful();
        $response->assertSeeInOrder([
            $stock7->id,
            $stock6->id,
            $stock5->id,
            $stock4->id,
            $stock3->id,
            $stock2->id,
        ]);
    }
}

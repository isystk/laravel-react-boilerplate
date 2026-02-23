<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Services\Api\Stock\IndexService;
use Exception;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class StockControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

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

    public function test_index_error(): void
    {
        $this->mock(IndexService::class, function ($mock) {
            $mock->shouldReceive('searchStock')->andThrow(new Exception('Stock error'));
        });

        $response = $this->getJson(route('api.stock'));

        $response->assertStatus(500);
        $response->assertJson([
            'result' => false,
            'error'  => ['messages' => ['Stock error']],
        ]);
    }
}

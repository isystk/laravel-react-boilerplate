<?php

namespace Feature\Http\Controllers\Api;

use App\Domain\Entities\Stock;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopControllerTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * 商品の一覧APIの取得テスト
     */
    public function testIndex(): void
    {
        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1'])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2'])->create();
        /** @var Stock $stock3 */
        $stock3 = Stock::factory(['name' => 'stock3'])->create();
        /** @var Stock $stock4 */
        $stock4 = Stock::factory(['name' => 'stock4'])->create();
        /** @var Stock $stock5 */
        $stock5 = Stock::factory(['name' => 'stock5'])->create();
        /** @var Stock $stock6 */
        $stock6 = Stock::factory(['name' => 'stock6'])->create();
        /** @var Stock $stock7 */
        $stock7 = Stock::factory(['name' => 'stock7'])->create();

        $response = $this->get(route('api.shops'));
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

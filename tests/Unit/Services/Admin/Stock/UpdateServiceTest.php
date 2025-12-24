<?php

namespace Tests\Unit\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Http\Requests\Admin\Stock\UpdateRequest;
use App\Services\Admin\Stock\UpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class UpdateServiceTest extends BaseTest
{
    use RefreshDatabase;

    private UpdateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UpdateService::class);
    }

    /**
     * updateのテスト
     */
    public function test_update(): void
    {
        Storage::fake();

        $stock = $this->createDefaultStock([
            'name' => 'aaa',
            'detail' => 'aaaの説明',
            'price' => 111,
            'quantity' => 1,
            'imgpath' => 'stock1.jpg',
        ]);

        $request = new UpdateRequest;
        $request['name'] = 'bbb';
        $request['detail'] = 'bbbの説明';
        $request['price'] = 222;
        $request['quantity'] = 2;
        $request['fileName'] = 'stock2.jpg';
        $request['imageBase64'] = 'data:image/jpeg;base64,xxxx';
        $this->service->update($stock->id, $request);

        // データが更新されたことをテスト
        $updatedStock = Stock::find($stock->id);
        $this->assertEquals('bbb', $updatedStock->name);
        $this->assertEquals('bbbの説明', $updatedStock->detail);
        $this->assertEquals(222, $updatedStock->price);
        $this->assertEquals(2, $updatedStock->quantity);
        $this->assertEquals('stock2.jpg', $updatedStock->imgpath);
    }
}

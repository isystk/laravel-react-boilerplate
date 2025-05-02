<?php

namespace Tests\Unit\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Services\Admin\Stock\CreateService;
use App\Http\Requests\Admin\Stock\StoreRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateServiceTest extends TestCase
{

    use RefreshDatabase;

    private CreateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CreateService::class);
    }

    /**
     * createのテスト
     */
    public function testCreate(): void
    {
        Storage::fake();

        $request = new StoreRequest();
        $request['name'] = 'aaa';
        $request['detail'] = 'aaaの説明';
        $request['price'] = 111;
        $request['quantity'] = 1;
        $request['fileName'] = 'stock1.jpg';
        $request['imageBase64'] = 'data:image/jpeg;base64,xxxx';
        $stock = $this->service->save($request);

        // データが更新されたことをテスト
        $updatedStock = Stock::find($stock->id);
        $this->assertEquals('aaa', $updatedStock->name);
        $this->assertEquals('aaaの説明', $updatedStock->detail);
        $this->assertEquals(111, $updatedStock->price);
        $this->assertEquals(1, $updatedStock->quantity);
        $this->assertEquals('stock1.jpg', $updatedStock->imgpath);
    }
}

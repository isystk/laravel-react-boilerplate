<?php

namespace Tests\Unit\Services\Admin\Stock;

use App\Domain\Entities\Image;
use App\Domain\Entities\Stock;
use App\Dto\Request\Admin\Stock\UpdateDto;
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

    public function test_update(): void
    {
        Storage::fake('s3');

        $stock = $this->createDefaultStock([
            'name'     => 'aaa',
            'detail'   => 'aaaの説明',
            'price'    => 111,
            'quantity' => 1,
        ]);

        $request                    = new UpdateRequest;
        $request['name']            = 'bbb';
        $request['detail']          = 'bbbの説明';
        $request['price']           = 222;
        $request['quantity']        = 2;
        $request['image_file_name'] = 'stock2.jpg';
        $request['image_base_64']   = 'data:image/jpeg;base64,xxxx';
        $dto                        = new UpdateDto($request);
        $this->service->update($stock->id, $dto);

        // データが更新されたことをテスト
        $updatedStock = Stock::find($stock->id);
        $this->assertEquals('bbb', $updatedStock->name);
        $this->assertEquals('bbbの説明', $updatedStock->detail);
        $this->assertEquals(222, $updatedStock->price);
        $this->assertEquals(2, $updatedStock->quantity);

        // 画像レコードが作成されたことをテスト
        $image = Image::find($updatedStock->image_id);
        $this->assertEquals('stock2.jpg', $image->file_name);

        // ファイルが存在することをテスト
        Storage::disk('s3')->assertExists('stock/stock2.jpg');
    }
}

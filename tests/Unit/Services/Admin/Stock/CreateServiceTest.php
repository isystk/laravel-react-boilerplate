<?php

namespace Tests\Unit\Services\Admin\Stock;

use App\Domain\Entities\Image;
use App\Domain\Entities\Stock;
use App\Dto\Request\Admin\Stock\CreateDto;
use App\Http\Requests\Admin\Stock\StoreRequest;
use App\Services\Admin\Stock\CreateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class CreateServiceTest extends BaseTest
{
    use RefreshDatabase;

    private CreateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('log');
        $admin = $this->createDefaultAdmin();
        $this->actingAs($admin, 'admin');
        $this->service = app(CreateService::class);
    }

    public function test_create(): void
    {
        Storage::fake('s3');

        $request                    = new StoreRequest;
        $request['name']            = 'aaa';
        $request['detail']          = 'aaaの説明';
        $request['price']           = 111;
        $request['quantity']        = 1;
        $request['image_file_name'] = 'stock1.jpg';
        $request['image_base_64']   = 'data:image/jpeg;base64,xxxx';
        $dto                        = new CreateDto($request);
        $stock                      = $this->service->save($dto);

        // データが更新されたことをテスト
        /** @var Stock $updatedStock */
        $updatedStock = Stock::find($stock->id);
        $this->assertEquals('aaa', $updatedStock->name);
        $this->assertEquals('aaaの説明', $updatedStock->detail);
        $this->assertEquals(111, $updatedStock->price);
        $this->assertEquals(1, $updatedStock->quantity);

        // 画像レコードが作成されたことをテスト
        $this->assertGreaterThan(0, $updatedStock->image_id);
        $image = Image::find($updatedStock->image_id);
        $this->assertEquals('stock1.jpg', $image->file_name);

        // ファイルが存在することをテスト
        Storage::disk('s3')->assertExists($image->getS3Path());
    }
}

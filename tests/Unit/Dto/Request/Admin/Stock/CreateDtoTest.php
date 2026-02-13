<?php

namespace Tests\Unit\Dto\Request\Admin\Stock;

use App\Dto\Request\Admin\Stock\CreateDto;
use App\Http\Requests\Admin\Stock\StoreRequest;
use Illuminate\Http\UploadedFile;
use Tests\BaseTest;

class CreateDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'name'            => 'テスト商品',
            'detail'          => '商品の説明文',
            'price'           => '1500',
            'quantity'        => '10',
            'image_file_name' => 'test.png',
        ]);

        $dto = new CreateDto($request);

        $this->assertSame('テスト商品', $dto->name);
        $this->assertSame('商品の説明文', $dto->detail);
        $this->assertSame(1500, $dto->price);
        $this->assertSame(10, $dto->quantity);
        $this->assertSame('test.png', $dto->imageFileName);
        $this->assertNull($dto->imageFile);
    }

    public function test_construct_imageBase64がある場合UploadedFileに変換されること(): void
    {
        // 1x1 pixel PNG
        $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $request = StoreRequest::create('/', 'POST', [
            'name'            => 'テスト商品',
            'detail'          => '説明',
            'price'           => '1000',
            'quantity'        => '5',
            'image_file_name' => 'test.png',
            'image_base_64'   => $base64,
        ]);

        $dto = new CreateDto($request);

        $this->assertInstanceOf(UploadedFile::class, $dto->imageFile);
    }

    public function test_construct_imageBase64がない場合imageFileがnullになること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'name'            => 'テスト商品',
            'detail'          => '説明',
            'price'           => '1000',
            'quantity'        => '5',
            'image_file_name' => 'test.png',
        ]);

        $dto = new CreateDto($request);

        $this->assertNull($dto->imageFile);
    }

    public function test_construct_priceとquantityがintにキャストされること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'name'            => 'テスト',
            'detail'          => '説明',
            'price'           => '2500',
            'quantity'        => '30',
            'image_file_name' => 'test.png',
        ]);

        $dto = new CreateDto($request);

        $this->assertIsInt($dto->price);
        $this->assertIsInt($dto->quantity);
        $this->assertSame(2500, $dto->price);
        $this->assertSame(30, $dto->quantity);
    }
}

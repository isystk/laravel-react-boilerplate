<?php

namespace Tests\Unit\Dto\Request\Admin\Stock;

use App\Dto\Request\Admin\Stock\UpdateDto;
use App\Http\Requests\Admin\Stock\UpdateRequest;
use Illuminate\Http\UploadedFile;
use Tests\BaseTest;

class UpdateDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = UpdateRequest::create('/', 'POST', [
            'name'     => '更新商品',
            'detail'   => '更新された説明文',
            'price'    => '2000',
            'quantity' => '20',
            'fileName' => 'updated.png',
        ]);

        $dto = new UpdateDto($request);

        $this->assertSame('更新商品', $dto->name);
        $this->assertSame('更新された説明文', $dto->detail);
        $this->assertSame(2000, $dto->price);
        $this->assertSame(20, $dto->quantity);
        $this->assertSame('updated.png', $dto->imageFileName);
        $this->assertNull($dto->imageFile);
    }

    public function test_construct_imageBase64がある場合UploadedFileに変換されること(): void
    {
        $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $request = UpdateRequest::create('/', 'POST', [
            'name'        => 'テスト',
            'detail'      => '説明',
            'price'       => '1000',
            'quantity'    => '5',
            'fileName'    => 'test.png',
            'imageBase64' => $base64,
        ]);

        $dto = new UpdateDto($request);

        $this->assertInstanceOf(UploadedFile::class, $dto->imageFile);
    }

    public function test_construct_imageBase64がない場合imageFileがnullになること(): void
    {
        $request = UpdateRequest::create('/', 'POST', [
            'name'     => 'テスト',
            'detail'   => '説明',
            'price'    => '1000',
            'quantity' => '5',
            'fileName' => 'test.png',
        ]);

        $dto = new UpdateDto($request);

        $this->assertNull($dto->imageFile);
    }
}

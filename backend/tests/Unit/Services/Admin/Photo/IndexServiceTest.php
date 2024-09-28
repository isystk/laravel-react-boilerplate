<?php

namespace Tests\Unit\Services\Admin\Photo;

use App\Enums\PhotoType;
use App\Services\Admin\Photo\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IndexServiceTest extends TestCase
{

    use RefreshDatabase;

    private IndexService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(IndexService::class, $this->service);
    }

    /**
     * searchPhotoListのテスト
     */
    public function testSearchPhotoList(): void
    {
        $default = [
            'file_name' => null,
            'file_type' => null,
        ];

        $photos = $this->service->searchPhotoList($default);
        $this->assertCount(0, $photos, '引数がない状態でエラーにならないことを始めにテスト');

        // テスト用のファイルを作成
        Storage::put('contact\contact1.jpg', '');
        Storage::put('stock\stock1.jpg', '');
        Storage::put('stock\stock2.jpg', '');

        $input = $default;
        $input['file_name'] = 'stock1.jpg';
        $photos = $this->service->searchPhotoList($input);
        $fileNames = collect($photos)->pluck('fileName')->all();
        $this->assertSame(['stock/stock1.jpg'], $fileNames, 'file_nameで検索が出来ることをテスト');

        $input = $default;
        $input['file_type'] = PhotoType::Stock->value;
        $photos = $this->service->searchPhotoList($input);
        $fileNames = collect($photos)->pluck('fileName')->all();
        $this->assertSame(['stock/stock1.jpg', 'stock/stock2.jpg'], $fileNames, 'file_nameで検索が出来ることをテスト');

        // テストファイルを削除
        Storage::delete('contact\contact1.jpg');
        Storage::delete('stock\stock1.jpg');
        Storage::delete('stock\stock2.jpg');
    }
}

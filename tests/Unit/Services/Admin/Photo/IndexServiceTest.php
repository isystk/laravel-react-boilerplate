<?php

namespace Tests\Unit\Services\Admin\Photo;

use App\Dto\Request\Admin\Photo\SearchConditionDto;
use App\Enums\PhotoType;
use App\Services\Admin\Photo\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\BaseTest;

class IndexServiceTest extends BaseTest
{
    use RefreshDatabase;

    private IndexService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    public function test_searchPhotoList(): void
    {
        $request = new Request([
            'sort_name'      => 'id',
            'sort_direction' => 'asc',
            'limit'          => 20,
        ]);
        $default = new SearchConditionDto($request);

        $images = $this->service->searchPhotoList($default)->getCollection();
        $this->assertSame(0, $images->count(), '引数がない状態でエラーにならないことを始めにテスト');

        // テスト用のImageレコードを作成
        $image1 = $this->createDefaultImage(['file_name' => 'stock1.jpg', 'type' => PhotoType::Stock->value]);
        $image2 = $this->createDefaultImage(['file_name' => 'stock2.jpg', 'type' => PhotoType::Stock->value]);
        $image3 = $this->createDefaultImage(['file_name' => 'contact1.jpg', 'type' => PhotoType::Contact->value]);

        // file_nameで検索
        $input           = clone $default;
        $input->fileName = 'stock1';
        $images          = $this->service->searchPhotoList($input)->getCollection();
        $imageIds        = $images->pluck('id')->all();
        $this->assertSame([$image1->id], $imageIds, 'file_nameで検索が出来ることをテスト');

        // file_typeで検索
        $input           = clone $default;
        $input->fileType = PhotoType::Stock->value;
        $images          = $this->service->searchPhotoList($input)->getCollection();
        $imageIds        = $images->pluck('id')->all();
        $this->assertSame([$image1->id, $image2->id], $imageIds, 'file_typeで検索が出来ることをテスト');
    }

    public function test_searchPhotoList_unusedOnly(): void
    {
        $request = new Request([
            'sort_name'      => 'id',
            'sort_direction' => 'asc',
            'limit'          => 20,
        ]);
        $default = new SearchConditionDto($request);

        // テスト用のImageレコードを作成
        $image1 = $this->createDefaultImage(['file_name' => 'unused.jpg', 'type' => PhotoType::Stock->value]);
        $image2 = $this->createDefaultImage(['file_name' => 'used_by_stock.jpg', 'type' => PhotoType::Stock->value]);
        $image3 = $this->createDefaultImage(['file_name' => 'used_by_contact.jpg', 'type' => PhotoType::Contact->value]);

        // Stockに参照されるImageを作成
        $this->createDefaultStock(['image_id' => $image2->id]);

        // ContactFormに参照されるImageを作成
        $this->createDefaultContactForm(['image_id' => $image3->id]);

        // unusedOnly=falseの場合は全件取得
        $input             = clone $default;
        $input->unusedOnly = false;
        $images            = $this->service->searchPhotoList($input)->getCollection();
        $this->assertSame(3, $images->count(), 'unusedOnly=falseの場合は全件取得');

        // unusedOnly=trueの場合は未参照のみ取得
        $input             = clone $default;
        $input->unusedOnly = true;
        $images            = $this->service->searchPhotoList($input)->getCollection();
        $imageIds          = $images->pluck('id')->all();
        $this->assertSame([$image1->id], $imageIds, 'unusedOnly=trueの場合は未参照のみ取得');
    }
}

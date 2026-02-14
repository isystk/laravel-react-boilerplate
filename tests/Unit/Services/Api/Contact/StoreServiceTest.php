<?php

namespace Tests\Unit\Services\Api\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Entities\Image;
use App\Dto\Request\Api\Contact\CreateDto;
use App\Enums\Age;
use App\Enums\Gender;
use App\Http\Requests\Api\Contact\StoreRequest;
use App\Services\Api\Contact\StoreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class StoreServiceTest extends BaseTest
{
    use RefreshDatabase;

    private StoreService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(StoreService::class);
    }

    public function test_update(): void
    {
        Storage::fake('s3');

        $request              = new StoreRequest;
        $request['user_name'] = 'aaa';
        $request['title']     = 'タイトル1';
        $request['email']     = 'aaa@test.com';
        $request['url']       = 'https://aaa.test.com';
        $request['gender']    = Gender::Male->value;
        $request['age']       = Age::Over30->value;
        $request['contact']   = 'お問い合わせ1';
        $dto                  = new CreateDto($request);
        $dto->imageFile       = UploadedFile::fake()->image('file1.jpg');
        $contact              = $this->service->save($dto);

        // データが登録されたことをテスト
        $updatedContact = Contact::find($contact->id);
        $this->assertEquals('aaa', $updatedContact->user_name);
        $this->assertEquals('タイトル1', $updatedContact->title);
        $this->assertEquals('aaa@test.com', $updatedContact->email);
        $this->assertEquals('https://aaa.test.com', $updatedContact->url);
        $this->assertEquals(Gender::Male, $updatedContact->gender);
        $this->assertEquals(Age::Over30, $updatedContact->age);
        $this->assertEquals('お問い合わせ1', $updatedContact->contact);

        // 画像レコードが作成されたことをテスト
        $this->assertNotNull($updatedContact->image_id);
        $image = Image::find($updatedContact->image_id);
        $this->assertEquals('file1.jpg', $image->file_name);

        // 新しい画像が登録されたこと
        Storage::disk('s3')->assertExists($image->getS3Path());
    }
}

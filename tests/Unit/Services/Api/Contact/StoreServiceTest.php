<?php

namespace Tests\Unit\Services\Api\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Entities\Image;
use App\Dto\Request\Api\Contact\CreateDto;
use App\Enums\ContactType;
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

        $user = $this->createDefaultUser();

        $request            = new StoreRequest;
        $request['title']   = 'タイトル1';
        $request['type']    = ContactType::Service->value;
        $request['message'] = 'お問い合わせ1';
        $dto                = new CreateDto($request);
        $dto->imageFile     = UploadedFile::fake()->image('file1.jpg');
        $contact            = $this->service->save($user, $dto);

        // データが登録されたことをテスト
        /** @var Contact $updatedContact */
        $updatedContact = Contact::find($contact->id);
        $this->assertEquals($user->id, $updatedContact->user_id);
        $this->assertEquals('タイトル1', $updatedContact->title);
        $this->assertEquals(ContactType::Service, $updatedContact->type);
        $this->assertEquals('お問い合わせ1', $updatedContact->message);

        // 画像レコードが作成されたことをテスト
        $this->assertNotNull($updatedContact->image_id);
        $image = Image::find($updatedContact->image_id);
        $this->assertEquals('file1.jpg', $image->file_name);

        // 新しい画像が登録されたこと
        Storage::disk('s3')->assertExists($image->getS3Path());
    }
}

<?php

namespace Tests\Unit\Services\Admin\ContactForm;

use App\Domain\Entities\Image;
use App\Dto\Request\Admin\ContactForm\UpdateDto;
use App\Enums\Age;
use App\Enums\Gender;
use App\Enums\PhotoType;
use App\Http\Requests\Admin\ContactForm\UpdateRequest;
use App\Services\Admin\ContactForm\UpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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

        $contactForm = $this->createDefaultContactForm([
            'user_name' => 'aaa',
            'title'     => 'タイトル1',
            'email'     => 'aaa@test.com',
            'url'       => 'https://aaa.test.com',
            'gender'    => Gender::Male->value,
            'age'       => Age::Over30->value,
            'contact'   => 'お問い合わせ1',
        ]);
        $image1 = $this->createDefaultImage([
            'file_name' => 'image1.jpg',
            'type'      => PhotoType::Contact,
        ]);
        Storage::disk('s3')->put($image1->getS3Path(), 'dummy');

        $request              = new UpdateRequest;
        $request['user_name'] = 'bbb';
        $request['title']     = 'タイトル2';
        $request['email']     = 'bbb@test.com';
        $request['url']       = 'https://bbb.test.com';
        $request['gender']    = Gender::Female->value;
        $request['age']       = Age::Over40->value;
        $request['contact']   = 'お問い合わせ2';
        $dto                  = new UpdateDto($request);
        $dto->imageFile       = UploadedFile::fake()->image('image2.jpg');
        $dto->imageFileName   = 'image2.jpg';
        $this->service->update($contactForm, $dto);

        // データが更新されたことをテスト
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'user_name' => 'bbb']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'title' => 'タイトル2']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'email' => 'bbb@test.com']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'url' => 'https://bbb.test.com']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'gender' => Gender::Female->value]);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'age' => Age::Over40->value]);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'contact' => 'お問い合わせ2']);

        // 新しい画像レコードが作成されたことをテスト
        $updatedContactForm = $contactForm->fresh();
        $image2             = Image::find($updatedContactForm->image_id);
        $this->assertEquals('image2.jpg', $image2->file_name);

        // お問い合わせから画像ファイルを削除しても、S3上にファイルが残っていることを確認
        Storage::disk('s3')->assertExists($image1->getS3Path());

        // 新しい画像がS3に登録されたことをテスト
        Storage::disk('s3')->assertExists($image2->getS3Path());
    }
}

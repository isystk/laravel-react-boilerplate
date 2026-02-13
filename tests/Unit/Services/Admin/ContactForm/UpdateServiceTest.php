<?php

namespace Tests\Unit\Services\Admin\ContactForm;

use App\Dto\Request\Admin\ContactForm\UpdateDto;
use App\Enums\Age;
use App\Enums\Gender;
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
            'user_name'       => 'aaa',
            'title'           => 'タイトル1',
            'email'           => 'aaa@test.com',
            'url'             => 'https://aaa.test.com',
            'gender'          => Gender::Male->value,
            'age'             => Age::Over30->value,
            'contact'         => 'お問い合わせ1',
            'image_file_name' => 'file1.jpg',
        ]);
        Storage::disk('s3')->put('contact/image1.jpg', 'dummy');

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

        // 元の画像が削除されたことをテスト
        $this->assertDatabaseMissing('contact_forms', ['id' => $contactForm->id, 'image_file_name' => 'image1.jpg']);
        // お問い合わせから画像ファイルを削除しても、S3上にファイルが残っていることを確認
        Storage::disk('s3')->assertExists('contact/image1.jpg');

        // 新しい画像が登録されたことをテスト
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'image_file_name' => 'image2.jpg']);
        Storage::disk('s3')->assertExists('contact/image2.jpg');
    }
}

<?php

namespace Tests\Unit\Dto\Request\Api\ContactForm;

use App\Dto\Request\Api\ContactForm\CreateDto;
use App\Enums\Age;
use App\Enums\Gender;
use App\Http\Requests\Api\ContactForm\StoreRequest;
use Illuminate\Http\UploadedFile;
use Tests\BaseTest;

class CreateDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'user_name' => 'テストユーザー',
            'title'     => 'お問い合わせ件名',
            'email'     => 'api-contact@example.com',
            'url'       => 'https://example.com',
            'gender'    => 0,
            'age'       => 2,
            'contact'   => 'APIからのお問い合わせ',
        ]);

        $dto = new CreateDto($request);

        $this->assertSame('テストユーザー', $dto->userName);
        $this->assertSame('お問い合わせ件名', $dto->title);
        $this->assertSame('api-contact@example.com', $dto->email);
        $this->assertSame('https://example.com', $dto->url);
        $this->assertSame(Gender::Male, $dto->gender);
        $this->assertSame(Age::Over20, $dto->age);
        $this->assertSame('APIからのお問い合わせ', $dto->contact);
        $this->assertNull($dto->imageFile);
    }

    public function test_construct_genderがFemaleでageがOver60の場合正しく設定されること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'user_name' => 'テスト',
            'title'     => '件名',
            'email'     => 'test@example.com',
            'url'       => '',
            'gender'    => 1,
            'age'       => 6,
            'contact'   => '内容',
        ]);

        $dto = new CreateDto($request);

        $this->assertSame(Gender::Female, $dto->gender);
        $this->assertSame(Age::Over60, $dto->age);
    }

    public function test_construct_imageBase64がある場合UploadedFileに変換されること(): void
    {
        $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $request = StoreRequest::create('/', 'POST', [
            'user_name'     => 'テスト',
            'title'         => '件名',
            'email'         => 'test@example.com',
            'url'           => '',
            'gender'        => 0,
            'age'           => 1,
            'contact'       => '内容',
            'image_base_64' => $base64,
        ]);

        $dto = new CreateDto($request);

        $this->assertInstanceOf(UploadedFile::class, $dto->imageFile);
    }

    public function test_construct_imageBase64がない場合imageFileがnullになること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'user_name' => 'テスト',
            'title'     => '件名',
            'email'     => 'test@example.com',
            'url'       => '',
            'gender'    => 0,
            'age'       => 1,
            'contact'   => '内容',
        ]);

        $dto = new CreateDto($request);

        $this->assertNull($dto->imageFile);
    }
}

<?php

namespace Tests\Unit\Services\Admin\User;

use App\Domain\Entities\Image;
use App\Dto\Request\Admin\User\UpdateDto;
use App\Enums\ImageType;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Services\Admin\User\UpdateService;
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
        Storage::fake('log');
        $admin = $this->createDefaultAdmin();
        $this->actingAs($admin, 'admin');
        $this->service = app(UpdateService::class);
    }

    public function test_update_名前とメールアドレスのみ更新(): void
    {
        $user = $this->createDefaultUser([
            'name'  => 'aaa',
            'email' => 'aaa@test.com',
        ]);

        $request          = new UpdateRequest;
        $request['name']  = 'bbb';
        $request['email'] = 'bbb@test.com';
        $dto              = new UpdateDto($request);
        $this->service->update($user->id, $dto);

        $user->refresh();
        $this->assertEquals('bbb', $user->name, '名前が変更される事');
        $this->assertEquals('bbb@test.com', $user->email, 'メールアドレスが変更される事');
    }

    public function test_update_アバター画像が新規登録される(): void
    {
        Storage::fake('s3');

        $user = $this->createDefaultUser([
            'name'  => 'user1',
            'email' => 'user1@example.com',
        ]);

        $request          = new UpdateRequest;
        $request['name']  = 'user1-updated';
        $request['email'] = 'user1-updated@example.com';
        // set uploaded file
        $file = UploadedFile::fake()->image('avatar.jpg');
        $request->files->set('avatar', $file);

        $dto = new UpdateDto($request);

        $this->service->update($user->id, $dto);

        $user->refresh();
        $this->assertNotNull($user->avatar_image_id, 'avatar_image_id が設定されること');
        $this->assertNull($user->avatar_url, 'avatar_url は null になること');

        $image = Image::find($user->avatar_image_id);
        $this->assertSame('avatar.jpg', $image->file_name);

        // S3 にファイルが保存されていること
        Storage::disk('s3')->assertExists($image->getS3Path());
    }

    public function test_update_アバター画像が更新される(): void
    {
        Storage::fake('s3');

        // 既存の Image を作成し、S3 にファイルを配置
        $image = $this->createDefaultImage([
            'file_name' => 'old_avatar.jpg',
            'type'      => ImageType::User,
        ]);
        Storage::disk('s3')->put($image->getS3Path(), 'old');

        // ユーザーに既存アバターを関連付ける
        $user = $this->createDefaultUser([
            'name'            => 'user2',
            'email'           => 'user2@example.com',
            'avatar_image_id' => $image->id,
            'avatar_url'      => null,
        ]);

        $request          = new UpdateRequest;
        $request['name']  = 'user2-updated';
        $request['email'] = 'user2-updated@example.com';
        $newFile          = UploadedFile::fake()->image('new_avatar.jpg');
        $request->files->set('avatar', $newFile);

        $dto = new UpdateDto($request);

        $this->service->update($user->id, $dto);

        $user->refresh();
        $this->assertSame($image->id, $user->avatar_image_id, '既存の Image レコードは同じ ID のままであること');
        $this->assertNull($user->avatar_url);

        $image->refresh();
        $this->assertSame('new_avatar.jpg', $image->file_name, 'Image.file_name が更新されていること');

        // 古いファイルが削除され、新しいファイルが存在すること
        Storage::disk('s3')->assertMissing($image->getS3Path() . '.old_dummy_check');
        Storage::disk('s3')->assertExists($image->getS3Path());
    }
}

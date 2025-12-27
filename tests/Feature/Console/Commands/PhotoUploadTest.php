<?php

namespace Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command;
use Tests\BaseTest;

class PhotoUploadTest extends BaseTest
{
    use RefreshDatabase;

    public function test_引数不正がある場合_エラー(): void
    {
        $command = $this->artisan('photo_upload', [
            '--file_name' => Str::random(33),
        ]);

        $command->expectsOutput(implode("\n", [
            'ファイル名(--file_name)には32文字以下の文字列を指定してください。',
        ]));
        $command->assertExitCode(Command::INVALID);
    }

    public function test_対象ファイルが存在しない場合(): void
    {
        Storage::fake();

        $command = $this->artisan('photo_upload', [
            '--run' => true,
        ]);

        $command->expectsOutput('ファイルが見つかりません。検索パス: ' . Storage::path('stocks'));
        $command->assertExitCode(Command::FAILURE);
    }

    public function test_対象ファイルが存在する場合(): void
    {
        Storage::fake('s3');
        $storage = Storage::disk('s3');

        $file = 'stocks/sample.jpg';
        $storage->put($file, 'dummy content');

        $command = $this->artisan('photo_upload', [
            '--run' => true,
        ]);
        $command->run();

        $command->expectsOutput("S3にアップロードしました。file={$file}");
        $storage->assertExists($file);
        $command->assertSuccessful();
    }

    public function test_ドライランの場合_s3にアップロードされないこと(): void
    {
        Storage::fake('s3');
        Storage::fake('local'); // デフォルトがlocalの場合

        $file = 'stocks/sample.jpg';
        Storage::put($file, 'dummy content');

        // --run オプションを指定しない
        $command = $this->artisan('photo_upload');

        $command->expectsOutput("S3にアップロードしました。file={$file}");
        $command->assertSuccessful();

        // S3にファイルが存在しないことを確認
        Storage::disk('s3')->assertMissing('stock/sample.jpg');
    }

    public function test_file_nameが指定された時_一致しないファイルはスキップされること(): void
    {
        Storage::fake();
        Storage::fake('s3');

        // 2つのファイルを準備
        Storage::put('stocks/target.jpg', 'content');
        Storage::put('stocks/other.jpg', 'content');

        $command = $this->artisan('photo_upload', [
            '--file_name' => 'target.jpg',
            '--run' => true,
        ]);

        // target.jpg のログは出るが、other.jpg のログは出ないことを確認
        $command->expectsOutput('S3にアップロードしました。file=stocks/target.jpg');
        $command->doesntExpectOutput('S3にアップロードしました。file=stocks/other.jpg');

        $command->assertSuccessful();
    }
}

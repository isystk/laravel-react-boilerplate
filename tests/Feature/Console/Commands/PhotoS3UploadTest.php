<?php

namespace Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command;
use Tests\BaseTest;

class PhotoS3UploadTest extends BaseTest
{
    use RefreshDatabase;

    public function test_引数不正がある場合_エラー(): void
    {
        $command = $this->artisan('s3upload', [
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

        $command = $this->artisan('s3upload', [
            '--run' => true,
        ]);
        $command->run();

        $command->expectsOutput('ファイルが見つかりません。検索パス: ' . Storage::path('stocks'));
        $command->assertExitCode(Command::FAILURE);
    }

    public function test_対象ファイルが存在する場合(): void
    {
        Storage::fake('s3');
        $storage = Storage::disk('s3');

        $file = 'stocks/sample.jpg';
        $storage->put($file, 'dummy content');

        $command = $this->artisan('s3upload', [
            '--run' => true,
        ]);
        $command->run();

        $command->expectsOutput("S3にアップロードしました。file={$file}");
        $storage->assertExists($file);
        $command->assertSuccessful();
    }
}

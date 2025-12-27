<?php

namespace Console\Commands;

use App\Console\Commands\BaseCommand;
use Illuminate\Console\OutputStyle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Tests\BaseTest;

class BaseCommandTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('log');
        Carbon::setTestNow('2025-01-01 10:00:00');
    }

    public function test_outputLog_ドライラン(): void
    {
        $command = $this->createTestCommand(false);

        $messages = ['Test Message 1', 'Test Message 2'];
        // @phpstan-ignore-next-line
        $command->executeOutputLog($messages);

        $dir = 'TestCommand'; // class_basename($this) の結果
        $fileName = '20250101.log';
        $path = "{$dir}/{$fileName}";

        // ファイルが存在しないことを確認
        Storage::disk('log')->assertMissing($path);
    }

    public function test_outputLog_本実行(): void
    {
        $command = $this->createTestCommand(true);

        $messages = ['Test Message 1', 'Test Message 2'];
        // @phpstan-ignore-next-line
        $command->executeOutputLog($messages);

        $dir = class_basename($command);
        $fileName = '20250101.log';
        $path = "{$dir}/{$fileName}";

        // ファイルが存在することを確認
        Storage::disk('log')->assertExists($path);

        // コンテンツの内容を確認
        $content = Storage::disk('log')->get($path);
        $this->assertStringContainsString('2025-01-01 10:00:00 Test Message 1', $content);
        $this->assertStringContainsString('2025-01-01 10:00:00 Test Message 2', $content);
    }

    /**
     * テスト用のBaseCommandインスタンスを作成する
     * @param bool $isRealRun 本実行フラグ
     * @return BaseCommand
     */
    private function createTestCommand(bool $isRealRun): BaseCommand
    {
        $command = new class extends BaseCommand {
            protected $signature = 'test:command';
            public bool $isRealRun;
            public function setIsRealRun(bool $value): void
            {
                $this->isRealRun = $value;
            }

            /**
             * @param array<int, string> $messages
             */
            public function executeOutputLog(array $messages): void
            {
                $this->outputLog($messages);
            }
        };
        $command->setIsRealRun($isRealRun);
        $command->setLaravel($this->app);
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $outputStyle = new OutputStyle($input, $output);
        $command->setOutput($outputStyle);
        return $command;
    }
}

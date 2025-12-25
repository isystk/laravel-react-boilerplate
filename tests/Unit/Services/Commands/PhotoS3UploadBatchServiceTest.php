<?php

namespace Services\Commands;

use App\Services\Commands\PhotoS3UploadBatchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\BaseTest;

class PhotoS3UploadBatchServiceTest extends BaseTest
{
    use RefreshDatabase;

    private PhotoS3UploadBatchService $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = app(PhotoS3UploadBatchService::class);
    }

    public function test_validateArgs(): void
    {
        $testCases = $this->getValidateArgsTestCases();
        foreach ($testCases as $key => $testCase) {
            $args = $testCase['args'];
            $expected = $testCase['expected'];

            $errors = $this->sut->validateArgs($args);
            $this->assertSame($expected, $errors);
        }
    }

    /**
     * validateArgs関数のテスト用データを返却する
     * @return array<string, array{
     *     args: array<string, mixed>,
     *     expected: array<int, string>
     * }>
     */
    private function getValidateArgsTestCases(): array
    {
        $safeArgs = [
            'file_name' => null,
        ];
        return [
            'OK : すべての正常な場合' => [
                'args' => $safeArgs,
                'expected' => [],
            ],
            'NG : ファイル名が32文字より大きい場合' => [
                'args' => [...$safeArgs, 'file_name' => Str::random(33)],
                'expected' => [
                    'ファイル名(--file_name)には32文字以下の文字列を指定してください。',
                ],
            ],
        ];
    }
}

<?php

namespace App\Utils;

use Illuminate\Http\UploadedFile;

class UploadImage
{
    public static function convertBase64(string $base64): UploadedFile
    {
        // Base64ヘッダーを取り除き、デコード
        $fileData = base64_decode((string) preg_replace('/^data:image\/\w+;base64,/', '', $base64));

        // 一時ファイルに保存
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'img_');

        try {
            file_put_contents($tmpFilePath, $fileData);

            $uploadedFile = new UploadedFile(
                $tmpFilePath,
                basename($tmpFilePath),
                mime_content_type($tmpFilePath),
                0,
                true
            );
        } catch (\Throwable $e) {
            if (file_exists($tmpFilePath)) {
                unlink($tmpFilePath);
            }

            throw $e;
        }

        // リクエスト終了時に一時ファイルを確実に削除
        register_shutdown_function(function () use ($tmpFilePath): void {
            if (file_exists($tmpFilePath)) {
                unlink($tmpFilePath);
            }
        });

        return $uploadedFile;
    }
}

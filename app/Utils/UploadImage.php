<?php

namespace App\Utils;

use Illuminate\Http\UploadedFile;

class UploadImage
{
    /**
     * Base64エンコードされた文字列から画像ファイルに変換します。
     */
    public static function convertBase64(string $base64): UploadedFile
    {
        // Base64ヘッダーを取り除き、デコード
        $fileData = base64_decode((string) preg_replace('/^data:image\/\w+;base64,/', '', $base64));

        // 一時ファイルに保存
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'img_');
        file_put_contents($tmpFilePath, $fileData);

        return new UploadedFile(
            $tmpFilePath,
            basename($tmpFilePath),
            mime_content_type($tmpFilePath),
            0,
            true
        );
    }
}

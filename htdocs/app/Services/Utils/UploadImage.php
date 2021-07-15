<?php

namespace App\Services\Utils;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class UploadImage
{

  public static function converBase64($base64)
  {

    // base64をデコード。プレフィックスに「data:image/jpeg;base64,」のような文字列がついている場合は除去して処理する。
    $data = explode(',', $base64);
    if (isset($data[1])) {
      $fileData = base64_decode($data[1]);
    } else {
      $fileData = base64_decode($data[0]);
    }

    // tmp領域に画像ファイルとして保存してUploadedFileとして扱う
    $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
    file_put_contents($tmpFilePath, $fileData);
    $tmpFile = new File($tmpFilePath);
    $file = new UploadedFile(
      $tmpFile->getPathname(),
      $tmpFile->getFilename(),
      $tmpFile->getMimeType(),
      0,
      true // Mark it as test, since the file isn't from real HTTP POST.
    );

    return $file;
  }
}

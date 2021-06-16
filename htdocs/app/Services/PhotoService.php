<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PhotoService
{
  public function __construct()
  {
  }

  public function searchPhoto($name)
  {

    $photos = [];

    $files = Storage::files();
    foreach ($files as $file) {
      $fileName = basename($file);
      if (empty($name) || strpos($fileName, $name) !== false) {
        $photo = (object) [
          'type' => 'default',
          'fileName' => $fileName
        ];
        array_push($photos, $photo);
      }
    }

    $files = Storage::files('stock');
    foreach ($files as $file) {
      $fileName = basename($file);
      if (empty($name) || strpos($fileName, $name) !== false) {
        $photo = (object) [
          'type' => 'stock',
          'fileName' => $fileName
        ];
        array_push($photos, $photo);
      }
    }
    // dd($photos);

    return $photos;
  }

  public function deletePhoto($type, $fileName)
  {

    if ($type === 'stock') {
      $delPath = storage_path('stock/' . $fileName);
      Storage::delete($delPath);
    } else {
      Storage::delete($fileName);
    }
  }
}

<?php

namespace App\Services;

use App\Enums\ErrorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoService extends Service
{
  public function __construct(
    Request $request
) {
    parent::__construct($request);
  }

  public function list()
  {

    $name = $this->request()->name;

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

  public function delete($type, $fileName)
  {

    if ($type === 'stock') {
      $delPath = 'stock/' . $fileName;
      Storage::delete($delPath);
    } else {
      Storage::delete($fileName);
    }
  }
}

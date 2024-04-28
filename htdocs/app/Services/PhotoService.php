<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoService extends BaseService
{
    public function __construct(
        Request $request
    )
    {
        parent::__construct($request);
    }

    /**
     * @return array<int, object>
     */
    public function list(): array
    {

        $name = $this->request()->name;

        $photos = [];

        $files = Storage::files();
        foreach ($files as $file) {
            $fileName = basename($file);
            if (empty($name) || strpos($fileName, $name) !== false) {
                $photo = (object)[
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
                $photo = (object)[
                    'type' => 'stock',
                    'fileName' => $fileName
                ];
                array_push($photos, $photo);
            }
        }

        return $photos;
    }

    /**
     * @param string $type
     * @param string $fileName
     */
    public function delete(string $type, string $fileName): void
    {

        if ($type === 'stock') {
            $delPath = 'stock/' . $fileName;
            Storage::delete($delPath);
        } else {
            Storage::delete($fileName);
        }
    }
}

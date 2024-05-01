<?php

namespace App\Services;

use App\Enums\PhotoType;
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

        $files = Storage::allFiles();
        foreach ($files as $file) {
            if (null !== $name && !str_contains($file, $name)) {
                continue;
            }
            $dirName = substr($file, 0, strpos($file, '/'));
            $photo = [
                'type' => PhotoType::getIdByDirName($dirName),
                'fileName' => $file,
            ];
           $photos[] = $photo;
        }

        return $photos;
    }

    /**
     * @param string $fileName
     */
    public function delete(string $fileName): void
    {
        Storage::delete($fileName);
    }
}

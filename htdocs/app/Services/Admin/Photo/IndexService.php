<?php

namespace App\Services\Admin\Photo;

use App\Enums\PhotoType;
use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;

class IndexService extends BaseService
{
    /**
     * @return array<array{
     *     type: ?PhotoType,
     *     fileName: string
     * }>
     */
    public function searchPhotoList(): array
    {
        $fileName = $this->request()->fileName;
        $fileType = is_string($this->request()->fileType) ? (int)$this->request()->fileType : null;

        $photos = [];
        /** @var array<string> $files */
        $files = Storage::allFiles();
        foreach ($files as $file) {
            if (null !== $fileName && !str_contains($file, $fileName)) {
                continue;
            }
            $dirName = substr($file, 0, strpos($file, '/'));
            $photoType = PhotoType::getIdByDirName($dirName);
            if (null !== $fileType && $fileType !== $photoType->value) {
                continue;
            }
            $photo = [
                'type' => $photoType,
                'fileName' => $file,
            ];
           $photos[] = $photo;
        }

        return $photos;
    }
}

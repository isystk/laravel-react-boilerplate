<?php

namespace App\Services\Admin\Photo;

use App\Enums\PhotoType;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndexService extends BaseService
{
    /**
     * リクエストパラメータから検索条件に変換します。
     *
     * @return array{
     *   file_name : ?string,
     *   file_type : ?int,
     * }
     */
    public function convertConditionsFromRequest(Request $request): array
    {
        $conditions = [
            'file_name' => $request->fileName,
            'file_type' => null,
        ];

        if (is_string($request->fileType)) {
            $conditions['file_type'] = (int) $request->fileType;
        }

        return $conditions;
    }

    /**
     * 写真を検索します。
     *
     * @param array{
     *   file_name : ?string,
     *   file_type : ?int,
     * } $conditions
     * @return array<array{
     *     type: ?PhotoType,
     *     fileName: string
     * }>
     */
    public function searchPhotoList(array $conditions): array
    {
        $photos = [];
        $storage = Storage::drive('s3');
        $stockFiles = $storage->allFiles('stock');
        $contactFiles = $storage->allFiles('contact');
        $files = array_merge($stockFiles, $contactFiles);
        foreach ($files as $file) {
            if (null !== $conditions['file_name'] && !str_contains($file, $conditions['file_name'])) {
                continue;
            }
            $type = substr($file, 0, strpos($file, '/'));
            if (empty($type)) {
                // ルート直下のファイルは何もしない
                continue;
            }
            $photoType = PhotoType::getByType($type);
            if (null !== $conditions['file_type'] && $conditions['file_type'] !== $photoType->value) {
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

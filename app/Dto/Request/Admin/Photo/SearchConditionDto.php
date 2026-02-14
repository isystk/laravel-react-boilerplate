<?php

namespace App\Dto\Request\Admin\Photo;

use Illuminate\Http\Request;

class SearchConditionDto
{
    // ファイル名
    public ?string $fileName;

    // ファイル種別
    public ?int $fileType;

    // 未参照のみ
    public bool $unusedOnly;

    // ソートのカラム名
    public string $sortName;

    // ソートの方向
    public string $sortDirection;

    // ページ
    public int $page;

    // リミット
    public int $limit;

    public function __construct(
        Request $request
    ) {
        $this->fileName      = $request->input('fileName');
        $this->fileType      = is_string($request->input('fileType')) && $request->input('fileType') !== '' ? (int) $request->input('fileType') : null;
        $this->unusedOnly    = (bool) $request->input('unusedOnly');
        $this->sortName      = $request->input('sort_name', 'id'); // デフォルト: id
        $this->sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc']) ? $request->input('sort_direction') : 'desc';
        $this->page          = (int) $request->input('page', 1); // デフォルト: 1
        $this->limit         = (int) $request->input('limit', 20); // デフォルト: 20
    }
}

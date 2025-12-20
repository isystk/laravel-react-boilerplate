<?php

namespace App\Dto\Request\Admin\Stock;

use Illuminate\Http\Request;

class SearchConditionDto
{
    // 名前
    public ?string $name;

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
        $this->name = $request->input('name');
        $this->sortName = $request->input('sort_name', 'id'); // デフォルト: id
        $this->sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc']) ? $request->input('sort_direction') : 'asc';
        $this->page = (int) $request->input('page', 1); // デフォルト: 1
        $this->limit = (int) $request->input('limit', 20); // デフォルト: 20
    }
}

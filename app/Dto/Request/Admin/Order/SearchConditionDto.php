<?php

namespace App\Dto\Request\Admin\Order;

use App\Utils\DateUtil;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class SearchConditionDto
{
    // 名前
    public ?string $name;

    // 注文日（From）
    public ?CarbonImmutable $orderDateFrom;

    // 注文日（To
    public ?CarbonImmutable $orderDateTo;

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
        $this->name          = $request->input('name');
        $this->orderDateFrom = DateUtil::toCarbon($request->input('order_date_from'));
        $this->orderDateTo   = DateUtil::toCarbon($request->input('order_date_to'));
        $this->sortName      = $request->input('sort_name', 'id'); // デフォルト: id
        $this->sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc']) ? $request->input('sort_direction') : 'desc';
        $this->page          = (int) $request->input('page', 1); // デフォルト: 1
        $this->limit         = (int) $request->input('limit', 20); // デフォルト: 20
    }
}

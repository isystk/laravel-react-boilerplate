<?php

namespace App\Dto\Request\Admin\Contact;

use App\Utils\DateUtil;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class SearchConditionDto
{
    // キーワード（ID / 名前 / メールアドレス）
    public ?string $keyword;

    // タイトル
    public ?string $title;

    // お問い合わせ日時（From）
    public ?CarbonImmutable $contactDateFrom;

    // お問い合わせ日時（To）
    public ?CarbonImmutable $contactDateTo;

    // 未返信のみ
    public bool $onlyUnreplied;

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
        $this->keyword         = $request->input('keyword');
        $this->title           = $request->input('title');
        $this->contactDateFrom = DateUtil::toCarbon($request->input('contact_date_from'));
        $this->contactDateTo   = DateUtil::toCarbon($request->input('contact_date_to'));
        // フォーム送信時はチェックボックスの値を使用、初回アクセス時はデフォルトでtrue
        $searched            = $request->boolean('searched', false);
        $this->onlyUnreplied = $searched ? $request->boolean('only_unreplied', false) : true;
        $this->sortName      = $request->input('sort_name', 'id'); // デフォルト: id
        $this->sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc']) ? $request->input('sort_direction') : 'desc';
        $this->page          = (int) $request->input('page', 1); // デフォルト: 1
        $this->limit         = (int) $request->input('limit', 20); // デフォルト: 20
    }
}

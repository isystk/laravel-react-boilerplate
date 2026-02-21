<?php

namespace App\Dto\Request\Admin\User;

use App\Enums\UserStatus;
use Illuminate\Http\Request;

class SearchConditionDto
{
    // キーワード（ID / 名前 / メールアドレス）
    public ?string $keyword;

    // ステータス
    public ?UserStatus $status;

    // Google連携あり
    public ?bool $hasGoogle;

    // 退会済みを含む
    public bool $withTrashed;

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
        $this->keyword  = $request->input('keyword') ?: null;
        $statusValue    = $request->input('status');
        $this->status   = ($statusValue !== null && $statusValue !== '') ? UserStatus::tryFrom((int) $statusValue) : null;
        $hasGoogleValue = $request->input('has_google');
        if ($hasGoogleValue === '1') {
            $this->hasGoogle = true;
        } elseif ($hasGoogleValue === '0') {
            $this->hasGoogle = false;
        } else {
            $this->hasGoogle = null;
        }
        $this->withTrashed   = $request->boolean('with_trashed', false);
        $this->sortName      = $request->input('sort_name', 'id'); // デフォルト: id
        $this->sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc']) ? $request->input('sort_direction') : 'desc';
        $this->page          = (int) $request->input('page', 1); // デフォルト: 1
        $this->limit         = (int) $request->input('limit', 20); // デフォルト: 20
    }
}

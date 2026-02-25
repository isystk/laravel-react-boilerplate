<?php

namespace App\Enums;

enum ContactType: int implements HasLabel
{
    /** サービスについて */
    case Service = 1;
    /** 不具合・トラブル */
    case Support = 2;
    /** その他 */
    case Other = 9;

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return __('enums.ContactType_' . $this->value);
    }
}

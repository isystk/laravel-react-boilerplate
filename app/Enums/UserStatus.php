<?php

namespace App\Enums;

enum UserStatus: int implements HasLabel
{
    /** 有効 */
    case Active = 0;
    /** アカウント停止 */
    case Suspended = 1;

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return __('enums.UserStatus_' . $this->value);
    }

    /**
     * 有効な状態の場合にTrueを返却する
     */
    public function isActive(): bool
    {
        return $this === self::Active;
    }
}

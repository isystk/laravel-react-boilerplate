<?php

namespace App\Enums;

enum AdminRole: string
{
    /** 管理者 */
    case Manager = 'manager';
    /** 上位管理者 */
    case HighManager = 'high-manager';

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return __('enums.AdminRole' . $this->value);
    }

    /**
     * 上位管理者の場合にTrueを返却する
     */
    public function isHighManager(): bool
    {
        return self::HighManager === $this;
    }
}

<?php

namespace App\Enums;

enum AdminRole: string
{
    /** 一般管理者 */
    case Manager = 'manager';
    /** システム管理者 */
    case HighManager = 'high-manager';

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return __('enums.AdminRole' . $this->value);
    }

    /**
     * システム管理者の場合にTrueを返却する
     */
    public function isHighManager(): bool
    {
        return $this === self::HighManager;
    }
}

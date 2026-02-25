<?php

namespace App\Enums;

enum AdminRole: string implements HasLabel
{
    /** スタッフ */
    case Staff = 'staff';
    /** システム管理者 */
    case SuperAdmin = 'super-admin';

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return __('enums.AdminRole_' . $this->value);
    }

    /**
     * システム管理者の場合にTrueを返却する
     */
    public function isSuperAdmin(): bool
    {
        return $this === self::SuperAdmin;
    }
}

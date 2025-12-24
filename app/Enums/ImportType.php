<?php

namespace App\Enums;

enum ImportType: int
{
    /** スタッフ */
    case Staff = 1;

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return __('enums.ImportType' . $this->value);
    }
}

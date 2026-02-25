<?php

namespace App\Enums;

enum ImportType: int implements HasLabel
{
    /** スタッフ */
    case Staff = 1;

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return __('enums.ImportType_' . $this->value);
    }
}

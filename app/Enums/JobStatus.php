<?php

namespace App\Enums;

enum JobStatus: int
{
    /** 処理待ち */
    case Waiting = 0;
    /** 処理中 */
    case Processing = 1;
    /** 正常終了 */
    case Success = 2;
    /** 異常終了 */
    case Failure = 9;

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return __('enums.JobStatus' . $this->value);
    }
}

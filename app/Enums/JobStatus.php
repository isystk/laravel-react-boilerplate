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

    /**
     * コードに紐づくEnumを返却する
     */
    public static function get(?int $code): ?JobStatus
    {
        if (null === $code) {
            return null;
        }
        foreach (self::cases() as $e) {
            if ($e->value === $code) {
                return $e;
            }
        }

        return null;
    }

    /**
     * 引数の値に紐づくラベルを返却する
     */
    public static function getLabel(?int $code): string
    {
        if (null === $code || null === self::get($code)) {
            return '';
        }

        return self::get($code)->label();
    }
}

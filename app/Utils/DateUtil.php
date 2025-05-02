<?php

namespace App\Utils;

use Carbon\CarbonImmutable;
use Exception;

class DateUtil
{

    /**
     * 日付文字列をCarbonに変換します。
     * @param ?string $date
     * @return CarbonImmutable|null
     */
    public static function toCarbonImmutable(?string $date): ?CarbonImmutable
    {
        if (null === $date || '' === $date) {
            // nullを返却する（デフォルトだと現在日時に変換される）
            return null;
        }

        // 年月のみの場合は、日を月初として変換する（デフォルトだと現在日が補完される）
        if (1 === substr_count($date, '-')) {
            $date .= '-01';
        } elseif (1 === substr_count($date, '/')) {
            $date .= '/01';
        }
        // 時分秒が含まれていない場合は、00:00:00 として変換する（デフォルトだと現在時間が補完される）
        if (0 === substr_count($date, ':')) {
            $date .= ' 00:00:00';
        }

        try {
            $carbon = CarbonImmutable::parse($date);
        } catch (Exception $e) {
            // 日付文字列が日付として不正な文字列だった場合
            return null;
        }
        return $carbon;
    }
}

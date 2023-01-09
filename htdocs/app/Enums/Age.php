<?php

namespace App\Enums;

enum Age: int
{
    /** ～19歳 */
    case Under19 = 1;
    /** 20歳～29歳 */
    case Over20 = 2;
    /** 30歳～39歳 */
    case Over30 = 3;
    /** 40歳～49歳 */
    case Over40 = 4;
    /** 50歳～59歳 */
    case Over50 = 5;
    /** 60歳～ */
    case Over60 = 6;

    /**
     * @param int $value
     * @return string
     */
    public static function getDescription(int $value): string
    {
        return __('enums.Age' . $value);
    }

}

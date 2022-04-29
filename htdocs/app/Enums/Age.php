<?php

namespace App\Enums;

enum Age: int
{
    /** @var int ～19歳 */
    case Under19 = 1;
    /** @var int 20歳～29歳 */
    case Over20 = 2;
    /** @var int 30歳～39歳 */
    case Over30 = 3;
    /** @var int 40歳～49歳 */
    case Over40 = 4;
    /** @var int 50歳～59歳 */
    case Over50 = 5;
    /** @var int 60歳～ */
    case Over60 = 6;

    public static function getDescription($value) {
        return __('enums.Age'. $value);
    }

}

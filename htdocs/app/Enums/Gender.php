<?php

namespace App\Enums;

enum Age: int
{

    /** @var int 男性 */
    case Male = 0;
    /** @var int 女性 */
    case Female = 1;

}

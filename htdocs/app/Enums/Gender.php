<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Gender extends Enum implements LocalizedEnum
{
  /** @var int 男性 */
  const Male = 0;
  /** @var int 女性 */
  const Female = 1;
}

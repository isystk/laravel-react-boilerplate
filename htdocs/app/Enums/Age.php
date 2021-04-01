<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Age extends Enum implements LocalizedEnum
{
  /** @var int ～19歳 */
  const Under19 = 1;
  /** @var int 20歳～29歳 */
  const Over20 = 2;
  /** @var int 30歳～39歳 */
  const Over30 = 3;
  /** @var int 40歳～49歳 */
  const Over40 = 4;
  /** @var int 50歳～59歳 */
  const Over50 = 5;
  /** @var int 60歳～ */
  const Over60 = 6;
}

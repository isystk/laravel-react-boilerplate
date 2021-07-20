<?php

namespace App\Constants;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 */
final class PhotoType extends Enum implements LocalizedEnum
{
  /** @var int 商品 */
  const Stock = '商品';
  /** @var int お問い合わせ */
  const Contact = 'お問い合わせ';
}

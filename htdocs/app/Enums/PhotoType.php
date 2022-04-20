<?php

namespace App\Enums;

enum PhotoType: String
{
    /** @var int 商品 */
    case Stock = '商品';
    /** @var int お問い合わせ */
    case Contact = 'お問い合わせ';

}

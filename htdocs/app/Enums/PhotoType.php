<?php

namespace App\Enums;

enum PhotoType: string
{
    /** 商品 */
    case Stock = '商品';
    /** お問い合わせ */
    case Contact = 'お問い合わせ';

}

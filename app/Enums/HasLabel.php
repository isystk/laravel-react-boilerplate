<?php

namespace App\Enums;

use BackedEnum;

interface HasLabel extends BackedEnum
{
    public function label(): string;
}

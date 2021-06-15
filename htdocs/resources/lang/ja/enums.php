<?php

use App\Enums\Gender;
use App\Enums\Age;

return [
    Gender::class => [
        Gender::Male => '男性',
        Gender::Female => '女性',
    ],
    Age::class => [
        Age::Under19 => '～19歳',
        Age::Over20 => '20歳～29歳',
        Age::Over30 => '30歳～39歳',
        Age::Over40 => '40歳～49歳',
        Age::Over50 => '50歳～59歳',
        Age::Over60 => '60歳～',
    ],
];


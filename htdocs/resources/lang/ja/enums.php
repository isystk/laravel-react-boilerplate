<?php

use App\Enums\Gender;
use App\Enums\Age;

return [
    'Gender' . Gender::Male->value => '男性',
    'Gender' . Gender::Female->value => '女性',
    'Age' . Age::Under19->value => '～19歳',
    'Age' . Age::Over20->value => '20歳～29歳',
    'Age' . Age::Over30->value => '30歳～39歳',
    'Age' . Age::Over40->value => '40歳～49歳',
    'Age' . Age::Over50->value => '50歳～59歳',
    'Age' . Age::Over60->value => '60歳～',
];


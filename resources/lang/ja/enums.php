<?php

use App\Enums\AdminRole;
use App\Enums\Age;
use App\Enums\Gender;
use App\Enums\ImageType;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use App\Enums\UserStatus;

return [
    'Gender' . Gender::Male->value              => '男性',
    'Gender' . Gender::Female->value            => '女性',
    'Age' . Age::Under19->value                 => '～19歳',
    'Age' . Age::Over20->value                  => '20歳～29歳',
    'Age' . Age::Over30->value                  => '30歳～39歳',
    'Age' . Age::Over40->value                  => '40歳～49歳',
    'Age' . Age::Over50->value                  => '50歳～59歳',
    'Age' . Age::Over60->value                  => '60歳～',
    'AdminRole' . AdminRole::Manager->value     => '一般管理者',
    'AdminRole' . AdminRole::HighManager->value => '上位管理者',
    'ImageType' . ImageType::Stock->value       => '商品',
    'ImageType' . ImageType::Contact->value     => 'お問い合わせ',
    'ImageType' . ImageType::User->value        => 'ユーザー',
    'JobStatus' . JobStatus::Waiting->value     => '処理待ち',
    'JobStatus' . JobStatus::Processing->value  => '処理中',
    'JobStatus' . JobStatus::Success->value     => '正常終了',
    'JobStatus' . JobStatus::Failure->value     => '異常終了',
    'ImportType' . ImportType::Staff->value     => 'スタッフ',
    'UserStatus' . UserStatus::Active->value    => '有効',
    'UserStatus' . UserStatus::Suspended->value => 'アカウント停止',
];

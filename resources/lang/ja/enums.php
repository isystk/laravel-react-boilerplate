<?php

use App\Enums\AdminRole;
use App\Enums\ContactType;
use App\Enums\ImageType;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use App\Enums\UserStatus;

return [
    'ContactType_' . ContactType::Service->value => 'サービスについて',
    'ContactType_' . ContactType::Support->value => '不具合・トラブル',
    'ContactType_' . ContactType::Other->value   => 'その他',
    'AdminRole_' . AdminRole::Staff->value       => 'スタッフ',
    'AdminRole_' . AdminRole::SuperAdmin->value  => 'システム管理者',
    'ImageType_' . ImageType::Stock->value       => '商品',
    'ImageType_' . ImageType::Contact->value     => 'お問い合わせ',
    'ImageType_' . ImageType::User->value        => 'ユーザー',
    'JobStatus_' . JobStatus::Waiting->value     => '処理待ち',
    'JobStatus_' . JobStatus::Processing->value  => '処理中',
    'JobStatus_' . JobStatus::Success->value     => '正常終了',
    'JobStatus_' . JobStatus::Failure->value     => '異常終了',
    'ImportType_' . ImportType::Staff->value     => 'スタッフ',
    'UserStatus_' . UserStatus::Active->value    => '有効',
    'UserStatus_' . UserStatus::Suspended->value => 'アカウント停止',
];

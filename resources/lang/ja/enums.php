<?php

use App\Enums\AdminRole;
use App\Enums\ContactType;
use App\Enums\ImageType;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use App\Enums\UserStatus;

return [
    'ContactType' . ContactType::Service->value => 'サービスについて',
    'ContactType' . ContactType::Support->value => '不具合・トラブル',
    'ContactType' . ContactType::Other->value   => 'その他',
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

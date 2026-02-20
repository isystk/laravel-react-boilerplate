<?php

use App\Enums\AdminRole;
use App\Enums\ContactType;
use App\Enums\ImageType;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use App\Enums\UserStatus;

return [
    'ContactType' . ContactType::Service->value => '关于服务',
    'ContactType' . ContactType::Support->value => '故障・投诉',
    'ContactType' . ContactType::Other->value   => '其他',
    'AdminRole' . AdminRole::Manager->value     => '普通管理员',
    'AdminRole' . AdminRole::HighManager->value => '系统管理员',
    'ImageType' . ImageType::Stock->value       => '商品',
    'ImageType' . ImageType::Contact->value     => '咨询',
    'ImageType' . ImageType::User->value        => '用户',
    'JobStatus' . JobStatus::Waiting->value     => '等待中',
    'JobStatus' . JobStatus::Processing->value  => '进行中',
    'JobStatus' . JobStatus::Success->value     => '正常结束',
    'JobStatus' . JobStatus::Failure->value     => '异常结束',
    'ImportType' . ImportType::Staff->value     => '员工',
    'UserStatus' . UserStatus::Active->value    => '有效',
    'UserStatus' . UserStatus::Suspended->value => '账号停用',
];

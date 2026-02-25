<?php

use App\Enums\AdminRole;
use App\Enums\ContactType;
use App\Enums\ImageType;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use App\Enums\UserStatus;

return [
    'ContactType_' . ContactType::Service->value => '关于服务',
    'ContactType_' . ContactType::Support->value => '故障・投诉',
    'ContactType_' . ContactType::Other->value   => '其他',
    'AdminRole_' . AdminRole::Staff->value       => '普通管理员',
    'AdminRole_' . AdminRole::SuperAdmin->value  => '系统管理员',
    'ImageType_' . ImageType::Stock->value       => '商品',
    'ImageType_' . ImageType::Contact->value     => '咨询',
    'ImageType_' . ImageType::User->value        => '用户',
    'JobStatus_' . JobStatus::Waiting->value     => '等待中',
    'JobStatus_' . JobStatus::Processing->value  => '进行中',
    'JobStatus_' . JobStatus::Success->value     => '正常结束',
    'JobStatus_' . JobStatus::Failure->value     => '异常结束',
    'ImportType_' . ImportType::Staff->value     => '员工',
    'UserStatus_' . UserStatus::Active->value    => '有效',
    'UserStatus_' . UserStatus::Suspended->value => '账号停用',
];

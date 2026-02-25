<?php

use App\Enums\AdminRole;
use App\Enums\ContactType;
use App\Enums\ImageType;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use App\Enums\UserStatus;

return [
    'ContactType_' . ContactType::Service->value => 'About Services',
    'ContactType_' . ContactType::Support->value => 'Technical Issues / Troubleshooting',
    'ContactType_' . ContactType::Other->value   => 'Other',
    'AdminRole_' . AdminRole::Manager->value     => 'Standard Administrator',
    'AdminRole_' . AdminRole::HighManager->value => 'System Administrator',
    'ImageType_' . ImageType::Stock->value       => 'Product',
    'ImageType_' . ImageType::Contact->value     => 'Inquiry',
    'ImageType_' . ImageType::User->value        => 'User',
    'JobStatus_' . JobStatus::Waiting->value     => 'Pending',
    'JobStatus_' . JobStatus::Processing->value  => 'Processing',
    'JobStatus_' . JobStatus::Success->value     => 'Success',
    'JobStatus_' . JobStatus::Failure->value     => 'Failed',
    'ImportType_' . ImportType::Staff->value     => 'Staff',
    'UserStatus_' . UserStatus::Active->value    => 'Active',
    'UserStatus_' . UserStatus::Suspended->value => 'Suspended',
];

<?php

use App\Enums\AdminRole;
use App\Enums\ContactType;
use App\Enums\ImageType;
use App\Enums\ImportType;
use App\Enums\JobStatus;
use App\Enums\UserStatus;

return [
    'ContactType' . ContactType::Service->value => 'About Services',
    'ContactType' . ContactType::Support->value => 'Technical Issues / Troubleshooting',
    'ContactType' . ContactType::Other->value   => 'Other',
    'AdminRole' . AdminRole::Manager->value     => 'Standard Administrator',
    'AdminRole' . AdminRole::HighManager->value => 'System Administrator',
    'ImageType' . ImageType::Stock->value       => 'Product',
    'ImageType' . ImageType::Contact->value     => 'Inquiry',
    'ImageType' . ImageType::User->value        => 'User',
    'JobStatus' . JobStatus::Waiting->value     => 'Pending',
    'JobStatus' . JobStatus::Processing->value  => 'Processing',
    'JobStatus' . JobStatus::Success->value     => 'Success',
    'JobStatus' . JobStatus::Failure->value     => 'Failed',
    'ImportType' . ImportType::Staff->value     => 'Staff',
    'UserStatus' . UserStatus::Active->value    => 'Active',
    'UserStatus' . UserStatus::Suspended->value => 'Suspended',
];

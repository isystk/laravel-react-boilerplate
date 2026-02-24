<?php

namespace Tests\Unit\Enums;

use App\Enums\JobStatus;
use Tests\BaseTest;

class JobStatusTest extends BaseTest
{
    public function test_label_各ケースのラベルが返却されること(): void
    {
        $this->assertSame(__('enums.JobStatus_0'), JobStatus::Waiting->label());
        $this->assertSame(__('enums.JobStatus_1'), JobStatus::Processing->label());
        $this->assertSame(__('enums.JobStatus_2'), JobStatus::Success->label());
        $this->assertSame(__('enums.JobStatus_9'), JobStatus::Failure->label());
    }
}

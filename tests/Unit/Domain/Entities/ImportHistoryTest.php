<?php

namespace Domain\Entities;

use App\Domain\Entities\ImportHistory;
use App\Enums\JobStatus;
use Tests\TestCase;

class ImportHistoryTest extends TestCase
{
    private ImportHistory $sub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new ImportHistory();
    }

    public function test_getStatus(): void
    {
        $this->sub->status = JobStatus::Success->value;
        $result = $this->sub->getStatus();
        $this->assertInstanceOf(JobStatus::class, $result);
        $this->assertSame(JobStatus::Success->value, $result->value);
    }
}

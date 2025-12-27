<?php

namespace Domain\Entities;

use App\Enums\ImportType;
use App\Enums\JobStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\BaseTest;

class ImportHistoryTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_正しくキャストされる事(): void
    {
        $model = $this->createDefaultImportHistory([
            'type' => ImportType::Staff->value,
            'status' => JobStatus::Success->value,
        ]);

        $this->assertInstanceOf(ImportType::class, $model->type);
        $this->assertInstanceOf(JobStatus::class, $model->status);
        $this->assertInstanceOf(Carbon::class, $model->created_at);
        $this->assertInstanceOf(Carbon::class, $model->updated_at);
    }
}

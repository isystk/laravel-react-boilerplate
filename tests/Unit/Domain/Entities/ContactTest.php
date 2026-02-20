<?php

namespace Tests\Unit\Domain\Entities;

use App\Enums\ContactType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\BaseTest;

class ContactTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_正しくキャストされる事(): void
    {
        $model = $this->createDefaultContact([
            'type' => ContactType::Service->value,
        ]);

        $this->assertInstanceOf(ContactType::class, $model->type);
        $this->assertInstanceOf(Carbon::class, $model->created_at);
        $this->assertInstanceOf(Carbon::class, $model->updated_at);
    }
}

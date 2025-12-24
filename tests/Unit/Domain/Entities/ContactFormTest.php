<?php

namespace Domain\Entities;

use App\Domain\Entities\ContactForm;
use App\Enums\Age;
use App\Enums\Gender;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ContactFormTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new ContactForm();
    }

    public function test_正しくキャストされる事(): void
    {
        $model = $this->createDefaultContactForm([
            'gender' => Gender::Male->value,
            'age' => Age::Under19->value,
        ]);

        $this->assertInstanceOf(Gender::class, $model->gender);
        $this->assertInstanceOf(Age::class, $model->age);
        $this->assertInstanceOf(Carbon::class, $model->created_at);
        $this->assertInstanceOf(Carbon::class, $model->updated_at);
    }
}

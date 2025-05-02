<?php

namespace Domain\Entities;

use App\Domain\Entities\ContactForm;
use App\Enums\Age;
use App\Enums\Gender;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    private ContactForm $sub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new ContactForm();
    }

    public function test_getGender(): void
    {
        $this->sub->gender = Gender::Male->value;
        $result = $this->sub->getGender();
        $this->assertInstanceOf(Gender::class, $result);
        $this->assertSame(Gender::Male->value, $result->value);
    }

    public function test_getAge(): void
    {
        $this->sub->age = Age::Over40->value;
        $result = $this->sub->getAge();
        $this->assertInstanceOf(Age::class, $result);
        $this->assertSame(Age::Over40->value, $result->value);
    }
}

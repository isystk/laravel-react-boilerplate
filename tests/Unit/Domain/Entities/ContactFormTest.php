<?php

namespace Domain\Entities;

use App\Domain\Entities\ContactForm;
use App\Enums\Age;
use App\Enums\Gender;
use Tests\BaseTest;

class ContactFormTest extends BaseTest
{
    private ContactForm $sub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new ContactForm;
    }

    public function test_get_gender(): void
    {
        $this->sub->gender = (bool) Gender::Male->value;
        $result = $this->sub->getGender();
        $this->assertInstanceOf(Gender::class, $result);
        $this->assertSame(Gender::Male, $result);
    }

    public function test_get_age(): void
    {
        $this->sub->age = Age::Over40->value;
        $result = $this->sub->getAge();
        $this->assertInstanceOf(Age::class, $result);
        $this->assertSame(Age::Over40, $result);
    }
}

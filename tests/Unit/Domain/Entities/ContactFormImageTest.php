<?php

namespace Domain\Entities;

use App\Domain\Entities\ContactForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form(): void
    {
        $contactFormImage = $this->createDefaultContactFormImage();

        $result = $contactFormImage->contactForm;
        $this->assertInstanceOf(ContactForm::class, $result);
        $this->assertSame($contactFormImage->contactForm->id, $result->id);
    }
}

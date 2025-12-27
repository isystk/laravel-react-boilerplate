<?php

namespace Domain\Entities;

use App\Domain\Entities\ContactForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ContactFormImageTest extends BaseTest
{
    use RefreshDatabase;

    public function test_contactForm(): void
    {
        $contactFormImage = $this->createDefaultContactFormImage();

        $result = $contactFormImage->contactForm;
        $this->assertInstanceOf(ContactForm::class, $result);
        $this->assertSame($contactFormImage->contactForm->id, $result->id);
    }
}

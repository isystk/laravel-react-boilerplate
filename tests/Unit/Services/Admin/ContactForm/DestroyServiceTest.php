<?php

namespace Tests\Unit\Services\Admin\ContactForm;

use App\Services\Admin\ContactForm\DestroyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class DestroyServiceTest extends BaseTest
{
    use RefreshDatabase;

    private DestroyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DestroyService::class);
    }

    public function test_delete(): void
    {
        $contactForm = $this->createDefaultContactForm();
        $contactFormImage = $this->createDefaultContactFormImage([
            'contact_form_id' => $contactForm->id,
        ]);

        $this->service->delete($contactForm->id);

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('contact_forms', ['id' => $contactForm->id]);
        $this->assertDatabaseMissing('contact_form_images', ['id' => $contactFormImage->id]);
    }
}

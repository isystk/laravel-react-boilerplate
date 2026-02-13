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

        $this->service->delete($contactForm->id);

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('contact_forms', ['id' => $contactForm->id]);
    }
}

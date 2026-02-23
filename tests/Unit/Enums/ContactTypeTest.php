<?php

namespace Tests\Unit\Enums;

use App\Enums\ContactType;
use Tests\BaseTest;

class ContactTypeTest extends BaseTest
{
    public function test_label_各ケースのラベルが返却されること(): void
    {
        $this->assertSame(__('enums.ContactType1'), ContactType::Service->label());
        $this->assertSame(__('enums.ContactType2'), ContactType::Support->label());
        $this->assertSame(__('enums.ContactType9'), ContactType::Other->label());
    }
}

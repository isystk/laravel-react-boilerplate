<?php

namespace Tests\Unit\Enums;

use App\Enums\ImportType;
use Tests\BaseTest;

class ImportTypeTest extends BaseTest
{
    public function test_label_各ケースのラベルが返却されること(): void
    {
        $this->assertSame(__('enums.ImportType_1'), ImportType::Staff->label());
    }
}

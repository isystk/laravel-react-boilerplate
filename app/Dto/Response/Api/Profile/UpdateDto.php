<?php

namespace App\Dto\Response\Api\Profile;

class UpdateDto
{
    public function __construct(
        public string $name,
        public ?string $avatarUrl,
    ) {}
}

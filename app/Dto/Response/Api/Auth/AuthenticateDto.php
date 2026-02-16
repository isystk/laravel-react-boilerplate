<?php

namespace App\Dto\Response\Api\Auth;

class AuthenticateDto
{
    public function __construct(
        public string $name,
        public ?string $avatarUrl,
    ) {}
}

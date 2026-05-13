<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs\Auth;

readonly class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
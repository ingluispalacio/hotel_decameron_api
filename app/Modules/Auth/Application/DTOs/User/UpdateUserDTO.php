<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs\User;

readonly class UpdateUserDTO
{
    public function __construct(
        public string $id,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $birthDate = null,
        public ?string $email = null,
        public ?string $address = null,
        public ?string $roleId = null,
        public ?string $status = null,
    ) {}
}
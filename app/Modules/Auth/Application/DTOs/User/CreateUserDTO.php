<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs\User;

use App\Modules\Auth\Domain\Enums\UserStatusEnum;

readonly class CreateUserDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $birthDate,
        public string $email,
        public string $password,
        public string $address,
        public string $roleId,
        public UserStatusEnum $status, 
    ) {}
}
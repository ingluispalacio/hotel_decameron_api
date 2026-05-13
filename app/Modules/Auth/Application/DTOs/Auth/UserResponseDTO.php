<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs\Auth;

use App\Modules\Auth\Domain\Enums\UserStatusEnum;

readonly class UserResponseDTO
{
    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $address,
        public string $roleId,
        public UserStatusEnum $status,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}
}
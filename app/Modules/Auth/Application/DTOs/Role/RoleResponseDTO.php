<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs\Role;

use App\Modules\Auth\Domain\Enums\UserRoleEnum;

readonly class RoleResponseDTO
{
    public function __construct(
        public string $id,
        public UserRoleEnum $title,
        public ?string $description,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}
}
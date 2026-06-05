<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs\Role;

use App\Shared\Enums\UserRoleEnum;

readonly class CreateRoleDTO
{
    public function __construct(
        public UserRoleEnum $title,
        public ?string $description = null,
    ) {}
}
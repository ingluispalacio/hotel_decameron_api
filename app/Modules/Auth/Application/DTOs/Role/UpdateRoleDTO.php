<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs\Role;

use App\Shared\Enums\UserRoleEnum;

readonly class UpdateRoleDTO
{
    public function __construct(
        public string $id,
        public UserRoleEnum $title,
        public ?string $description = null,
    ) {}
}
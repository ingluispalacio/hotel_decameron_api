<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs\Auth;

readonly class ChangePasswordDTO
{
    public function __construct(
        public string $userId,
        public string $currentPassword,
        public string $newPassword,
        public string $newPasswordConfirmation,
    ) {}
}
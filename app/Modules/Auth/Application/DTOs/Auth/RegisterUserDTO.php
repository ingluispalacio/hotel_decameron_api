<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs\Auth;

use App\Modules\Auth\Domain\Enums\UserStatusEnum;

readonly class RegisterUserDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $birthDate, // formato Y-m-d o \DateTimeImmutable? Mantenemos string para simplificar
        public string $email,
        public string $password,
        public string $address,
        public string $roleId,
        public ?UserStatusEnum $status = UserStatusEnum::ACTIVE,
    ) {}
}
<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Mappers;

use App\Modules\Auth\Domain\Entities\User;
use App\Modules\Auth\Domain\Enums\UserStatusEnum;
use App\Modules\Auth\Application\DTOs\Auth\RegisterUserDTO;
use App\Modules\Auth\Application\DTOs\User\CreateUserDTO;
use App\Modules\Auth\Application\DTOs\User\UpdateUserDTO;
use Ramsey\Uuid\Uuid;

class UserEntityMapper
{
    /**
     * Convierte un RegisterUserDTO en una nueva entidad User.
     * Genera un nuevo UUID y asigna el estado predeterminado (ACTIVE).
     */
    public static function toEntityFromRegisterDTO(RegisterUserDTO $dto): User
    {
        return new User(
            id: Uuid::uuid4()->toString(),
            firstName: $dto->firstName,
            lastName: $dto->lastName,
            birthDate: new \DateTimeImmutable($dto->birthDate),
            roleId: $dto->roleId,
            email: $dto->email,
            password: $dto->password, // Se encriptará en el caso de uso o en el repositorio
            address: $dto->address,
            status: UserStatusEnum::ACTIVE, // o un valor por defecto
            createdAt: null,
            updatedAt: null
        );
    }

    /**
     * Convierte un CreateUserDTO en una nueva entidad User (para administración).
     * Genera nuevo UUID y usa el estado proporcionado.
     */
    public static function toEntityFromCreateDTO(CreateUserDTO $dto): User
    {
        return new User(
            id: Uuid::uuid4()->toString(),
            firstName: $dto->firstName,
            lastName: $dto->lastName,
            birthDate: new \DateTimeImmutable($dto->birthDate),
            roleId: $dto->roleId,
            email: $dto->email,
            password: $dto->password,
            address: $dto->address,
            status: UserStatusEnum::from($dto->status->value), // Convertir el enum si es necesario
            createdAt: null,
            updatedAt: null
        );
    }


}
<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Mappers;

use App\Modules\Auth\Domain\Entities\Role;
use App\Modules\Auth\Application\DTOs\Role\CreateRoleDTO;
use App\Modules\Auth\Application\DTOs\Role\UpdateRoleDTO;
use App\Modules\Auth\Domain\Enums\UserRoleEnum;
use Ramsey\Uuid\Uuid;

class RoleEntityMapper
{
    /**
     * Convierte un CreateRoleDTO en una nueva entidad Role.
     */
    public static function toEntityFromCreateDTO(CreateRoleDTO $dto): Role
    {
        $titleString = $dto->title instanceof UserRoleEnum 
            ? $dto->title->value 
            : $dto->title;

        return new Role(
            id: Uuid::uuid4()->toString(),
           title: $titleString,
            description: $dto->description,
            createdAt: null,
            updatedAt: null
        );
    }

    /**
     * Convierte un UpdateRoleDTO en una entidad Role (reutilizando el ID).
     * Al igual que con User, es preferible actualizar la entidad existente con setters.
     */
    public static function toEntityFromUpdateDTO(UpdateRoleDTO $dto): Role
    {
        $titleString = $dto->title instanceof UserRoleEnum 
            ? $dto->title->value 
            : $dto->title;

        return new Role(
            id: $dto->id,
            title: $titleString,
            description: $dto->description,
            createdAt: null,
            updatedAt: null
        );
    }
}
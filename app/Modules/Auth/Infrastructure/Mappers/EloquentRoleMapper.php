<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Mappers;

use App\Modules\Auth\Domain\Entities\Role as DomainRole;
use App\Modules\Auth\Infrastructure\Models\Role as EloquentRole;

class EloquentRoleMapper
{
    public static function toDomain(EloquentRole $eloquent): DomainRole
    {
        // Usar toDateTimeImmutable() en lugar de new DateTimeImmutable()
        $role = new DomainRole(
            id: $eloquent->id,
            title: $eloquent->title,
            description: $eloquent->description,
            createdAt: $eloquent->created_at?->toDateTimeImmutable(),
            updatedAt: $eloquent->updated_at?->toDateTimeImmutable(),
        );
        if ($eloquent->deleted_at !== null) {
            $role->setDeletedAt($eloquent->deleted_at->toDateTimeImmutable());
        }
        return $role;
    }

    public static function toEloquent(DomainRole $role): array
    {
        return [
            'id'          => $role->getId(),
            'title'       => $role->getTitle(),
            'description' => $role->getDescription(),
            'created_at'  => $role->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at'  => $role->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'deleted_at'  => $role->getDeletedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}
<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Infrastructure\Mappers;

use App\Modules\Hotel\Domain\Entities\RoomType as DomainRoomType;
use App\Modules\Hotel\Domain\Enums\RoomTypeEnum;
use App\Modules\Hotel\Infrastructure\Models\RoomType as EloquentRoomType;

class EloquentRoomTypeMapper
{
    /**
     * Convierte un modelo Eloquent en una entidad de dominio.
     */
    public static function toDomain(EloquentRoomType $eloquent): DomainRoomType
    {
        $enumName = RoomTypeEnum::from($eloquent->name); // may throw if invalid
        $roomType = new DomainRoomType($eloquent->id, $enumName);
        if ($eloquent->deleted_at !== null) {
            $roomType->setDeletedAt($eloquent->deleted_at->toDateTimeImmutable());
        }
        return $roomType;
    }

    /**
     * Convierte una entidad de dominio en un array para ser usado por Eloquent.
     */
    public static function toEloquent(DomainRoomType $roomType): array
    {
        return [
            'id'         => $roomType->getId(),
            'name'       => $roomType->getName(),
            'deleted_at' => $roomType->getDeletedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}

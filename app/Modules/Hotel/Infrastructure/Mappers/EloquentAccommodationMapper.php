<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Infrastructure\Mappers;

use App\Modules\Hotel\Domain\Entities\Accommodation as DomainAccommodation;
use App\Modules\Hotel\Infrastructure\Models\Accommodation as EloquentAccommodation;

class EloquentAccommodationMapper
{
    /**
     * Convierte un modelo Eloquent en una entidad de dominio.
     */
    public static function toDomain(EloquentAccommodation $eloquent): DomainAccommodation
    {
        $accommodation = new DomainAccommodation($eloquent->id, $eloquent->name);
        if ($eloquent->deleted_at !== null) {
            $accommodation->setDeletedAt(new \DateTimeImmutable($eloquent->deleted_at));
        }
        return $accommodation;
    }

    /**
     * Convierte una entidad de dominio en un array para ser usado por Eloquent.
     */
    public static function toEloquent(DomainAccommodation $accommodation): array
    {
        return [
            'id'         => $accommodation->getId(),
            'name'       => $accommodation->getName(),
            'deleted_at' => $accommodation->getDeletedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}
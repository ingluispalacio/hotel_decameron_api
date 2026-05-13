<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Infrastructure\Mappers;

use App\Modules\Hotel\Domain\Entities\Hotel as DomainHotel;
use App\Modules\Hotel\Infrastructure\Models\Hotel as EloquentHotel;

class EloquentHotelMapper
{
    /**
     * Convierte un modelo Eloquent a una entidad de dominio.
     */
    public static function toDomain(EloquentHotel $eloquent): DomainHotel
    {
        $hotel = new DomainHotel(
            $eloquent->id,
            $eloquent->name,
            $eloquent->address,
            $eloquent->city_id,
            $eloquent->nit,
            $eloquent->max_rooms
        );

        // Si usas soft delete y la entidad tiene el campo deleted_at
        if ($eloquent->deleted_at !== null) {
            $hotel->setDeletedAt(new \DateTimeImmutable($eloquent->deleted_at));
        }

        return $hotel;
    }

    /**
     * Convierte una entidad de dominio a un array para Eloquent.
     */
    public static function toEloquent(DomainHotel $hotel): array
    {
        return [
            'id'         => $hotel->getId(),
            'name'       => $hotel->getName(),
            'address'    => $hotel->getAddress(),
            'city_id'    => $hotel->getCityId(),
            'nit'        => $hotel->getNit(),
            'max_rooms'  => $hotel->getMaxRooms(),
            'deleted_at' => $hotel->isDeleted() ? $hotel->getDeletedAt()?->format('Y-m-d H:i:s') : null,
        ];
    }
}
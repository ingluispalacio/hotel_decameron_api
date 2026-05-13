<?php

namespace App\Modules\Hotel\Infrastructure\Mappers;

use App\Modules\Hotel\Domain\Entities\HotelConfiguration as DomainHotelConfiguration;
use App\Modules\Hotel\Infrastructure\Models\HotelConfiguration as EloquentHotelConfiguration;

class EloquentHotelConfigurationMapper
{
    public static function toDomain(EloquentHotelConfiguration $eloquent): DomainHotelConfiguration
    {
        $config = new DomainHotelConfiguration(
            $eloquent->id,
            $eloquent->hotel_id,
            $eloquent->room_type_id,
            $eloquent->accommodation_id,
            $eloquent->quantity
        );
        if ($eloquent->deleted_at !== null) {
            $config->setDeletedAt(new \DateTimeImmutable($eloquent->deleted_at));
        }
        return $config;
    }

    public static function toEloquent(DomainHotelConfiguration $config): array
    {
        return [
            'id'              => $config->getId(),
            'hotel_id'        => $config->getHotelId(),
            'room_type_id'    => $config->getRoomTypeId(),
            'accommodation_id'=> $config->getAccommodationId(),
            'quantity'        => $config->getQuantity(),
            'deleted_at'      => $config->isDeleted() ? $config->getDeletedAt()?->format('Y-m-d H:i:s') : null,
        ];
    }
}
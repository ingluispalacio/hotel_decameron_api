<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Infrastructure\Repositories;

use App\Modules\Hotel\Domain\Entities\HotelConfiguration as DomainHotelConfiguration;
use App\Modules\Hotel\Domain\Repositories\HotelConfigurationRepositoryInterface;
use App\Modules\Hotel\Infrastructure\Models\HotelConfiguration as EloquentHotelConfiguration;
use App\Modules\Hotel\Infrastructure\Mappers\EloquentHotelConfigurationMapper;


class HotelConfigurationRepository implements HotelConfigurationRepositoryInterface
{
    public function save(DomainHotelConfiguration $configuration): void
    {
        $data = EloquentHotelConfigurationMapper::toEloquent($configuration);
        $eloquent = EloquentHotelConfiguration::find($configuration->getId()) ?? new EloquentHotelConfiguration();
        $eloquent->fill($data);
        $eloquent->save();
    }

    public function findById(string $id): ?DomainHotelConfiguration
    {
        $eloquent = EloquentHotelConfiguration::withTrashed()->find($id);
        if (!$eloquent) {
            return null;
        }
        return EloquentHotelConfigurationMapper::toDomain($eloquent);
    }

    public function delete(DomainHotelConfiguration $configuration): void
    {
        $eloquent = EloquentHotelConfiguration::find($configuration->getId());
        if ($eloquent) {
            $eloquent->delete();
        }
    }

    public function existsCombination(
        string $hotelId,
        string $roomTypeId,
        string $accommodationId,
        ?string $ignoreId = null
    ): bool {
        $query = EloquentHotelConfiguration::query()
            ->where('hotel_id', $hotelId)
            ->where('room_type_id', $roomTypeId)
            ->where('accommodation_id', $accommodationId);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    public function sumQuantityByHotel(string $hotelId, ?string $excludeId = null): int
    {
        $query = EloquentHotelConfiguration::where('hotel_id', $hotelId);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return (int) $query->sum('quantity');
    }
}
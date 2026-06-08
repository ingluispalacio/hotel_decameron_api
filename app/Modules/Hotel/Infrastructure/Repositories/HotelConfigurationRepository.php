<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Infrastructure\Repositories;

use App\Modules\Hotel\Domain\Entities\HotelConfiguration as DomainHotelConfiguration;
use App\Modules\Hotel\Domain\Repositories\HotelConfigurationRepositoryInterface;
use App\Modules\Hotel\Infrastructure\Models\HotelConfiguration as EloquentHotelConfiguration;
use App\Modules\Hotel\Infrastructure\Mappers\EloquentHotelConfigurationMapper;
use App\Modules\Hotel\Infrastructure\Models\Hotel;

class HotelConfigurationRepository implements HotelConfigurationRepositoryInterface
{
    public function save(DomainHotelConfiguration $configuration): void
    {
        $data = EloquentHotelConfigurationMapper::toEloquent($configuration);
        $eloquent = EloquentHotelConfiguration::find($configuration->getId()) ?? new EloquentHotelConfiguration();
        $eloquent->fill($data);
        $eloquent->save();
    }

    public function findById(string $id): ?array
    {
        $hotel = Hotel::with([
            'configurations.roomType',
            'configurations.accommodation'
        ])->find($id);

        if (!$hotel) {
            return null;
        }

        return [
            'id' => $hotel->id,
            'hotel_name' => $hotel->name,
            'max_rooms' => $hotel->max_rooms,
            'configurations' => $hotel->configurations->map(function ($config) {
                return [
                    'id' => $config->id,
                    'room_type' => $config->roomType->name,
                    'accommodation' => $config->accommodation->name,
                    'quantity' => $config->quantity,
                ];
            })->toArray(),
        ];
    }

    public function delete(DomainHotelConfiguration $configuration): void
    {
        $eloquent = EloquentHotelConfiguration::find($configuration->getId());
        if ($eloquent) {
            $eloquent->delete();
        }
    }

    public function findConfigurationById(string $id): ?DomainHotelConfiguration
    {
        $eloquent = EloquentHotelConfiguration::withTrashed()->find($id);
        if (!$eloquent) {
            return null;
        }
        return EloquentHotelConfigurationMapper::toDomain($eloquent);
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

    public function findAll(): array
    {
        $hotels = Hotel::with(['configurations.roomType', 'configurations.accommodation'])->get();

        return $hotels->map(function ($hotel) {
            return [
                'id' => $hotel->id,
                'hotel_name' => $hotel->name,
                'max_rooms' => $hotel->max_rooms,
                'configurations' => $hotel->configurations->map(function ($config) {
                    return [
                        'room_type' => $config->roomType->name,
                        'accommodation' => $config->accommodation->name,
                        'quantity' => $config->quantity,
                    ];
                })->toArray(),
            ];
        })->toArray();
    }
}

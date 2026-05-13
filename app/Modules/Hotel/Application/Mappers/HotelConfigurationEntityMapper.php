<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Application\Mappers;

use App\Modules\Hotel\Domain\Entities\HotelConfiguration;
use App\Modules\Hotel\Application\DTOs\HotelConfiguration\CreateHotelConfigurationDTO;
use App\Modules\Hotel\Application\DTOs\HotelConfiguration\UpdateHotelConfigurationDTO;
use Ramsey\Uuid\Uuid;

class HotelConfigurationEntityMapper
{
    /**
     * Convierte un CreateHotelConfigurationDTO en una entidad HotelConfiguration.
     * Genera un nuevo UUID automáticamente.
     */
    public static function toEntityFromCreateDTO(CreateHotelConfigurationDTO $dto): HotelConfiguration
    {
        return new HotelConfiguration(
            Uuid::uuid4()->toString(),
            $dto->hotelId,
            $dto->roomTypeId,
            $dto->accommodationId,
            $dto->quantity
        );
    }

    /**
     * Convierte un UpdateHotelConfigurationDTO en una entidad HotelConfiguration.
     * Usa el ID existente del DTO.
     */
    public static function toEntityFromUpdateDTO(UpdateHotelConfigurationDTO $dto): HotelConfiguration
    {
        return new HotelConfiguration(
            $dto->id,
            $dto->hotelId,
            $dto->roomTypeId,
            $dto->accommodationId,
            $dto->quantity
        );
    }

    public static function toEntityFromArray(array $data): HotelConfiguration
    {
        $id = $data['id'] ?? Uuid::uuid4()->toString();
        return new HotelConfiguration(
            $id,
            $data['hotel_id'],
            $data['room_type_id'],
            $data['accommodation_id'],
            (int) $data['quantity']
        );
    }
}
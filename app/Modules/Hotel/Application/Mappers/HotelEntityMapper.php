<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Application\Mappers;

use App\Modules\Hotel\Application\DTOs\Hotel\CreateHotelDTO;
use App\Modules\Hotel\Application\DTOs\Hotel\UpdateHotelDTO;
use App\Modules\Hotel\Domain\Entities\Hotel;
use Ramsey\Uuid\Uuid;

class HotelEntityMapper
{
    /**
     * Convierte un CreateHotelDTO en una nueva entidad Hotel.
     * Genera un nuevo UUID automáticamente.
     */
    public static function toEntityFromCreateDTO(CreateHotelDTO $dto): Hotel
    {
        return new Hotel(
            Uuid::uuid4()->toString(),
            $dto->name,
            $dto->address,
            $dto->cityId,
            $dto->nit,
            $dto->maxRooms
        );
    }

    /**
     * Convierte un UpdateHotelDTO en una entidad Hotel.
     * Reutiliza el ID existente del DTO.
     */
    public static function toEntityFromUpdateDTO(UpdateHotelDTO $dto): Hotel
    {
        return new Hotel(
            $dto->id,
            $dto->name,
            $dto->address,
            $dto->cityId,
            $dto->nit,
            $dto->maxRooms
        );
    }

    /**
     * Si necesitas construir una entidad a partir de un array (por ejemplo, desde el repositorio al leer).
     * Este método es útil para el mapeo inverso (Eloquent → entidad), pero como tienes un mapper para infraestructura,
     * es probable que no lo uses aquí. Lo dejo por si acaso.
     */
    public static function toEntityFromArray(array $data): Hotel
    {
        return new Hotel(
            $data['id'],
            $data['name'],
            $data['address'],
            $data['city_id'],      // o cityId según cómo manejes la clave
            $data['nit'],
            (int) $data['max_rooms']
        );
    }
}
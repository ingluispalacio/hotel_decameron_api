<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Application\Mappers;

use App\Modules\Hotel\Domain\Entities\City;
use App\Modules\Hotel\Application\DTOs\City\CreateCityDTO;
use Ramsey\Uuid\Uuid;

class CityEntityMapper
{
    /**
     * Convierte un CreateCityDTO en una entidad City (genera nuevo UUID).
     */
    public static function toEntityFromCreateDTO(CreateCityDTO $dto): City
    {
        return new City(
            Uuid::uuid4()->toString(),
            $dto->name
        );
    }

    public static function toEntityFromArray(array $data): City
    {
        $id = $data['id'] ?? Uuid::uuid4()->toString();
        return new City($id, $data['name']);
    }
}
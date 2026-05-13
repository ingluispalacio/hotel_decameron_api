<?php

namespace App\Modules\Hotel\Domain\Repositories;

use App\Modules\Hotel\Domain\Entities\HotelConfiguration;

interface HotelConfigurationRepositoryInterface
{
    // Persistir una configuración (crea o actualiza según si tiene ID)
    public function save(HotelConfiguration $configuration): void;

    // Buscar por ID
    public function findById(string $id): ?HotelConfiguration;

    // Eliminar una configuración (física o lógica, según decidas)
    public function delete(HotelConfiguration $configuration): void;

    // Verificar si ya existe una combinación específica (para unicidad)
    public function existsCombination(
        string $hotelId,
        string $roomTypeId,
        string $accommodationId,
        ?string $ignoreId = null
    ): bool;

    // Sumar la cantidad total de habitaciones configuradas para un hotel, excluyendo opcionalmente un ID
    public function sumQuantityByHotel(string $hotelId, ?string $excludeId = null): int;

  
}
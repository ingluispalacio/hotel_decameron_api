<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Application\Services;

use App\Modules\Hotel\Application\DTOs\Hotel\CreateHotelDTO;
use App\Modules\Hotel\Application\DTOs\Hotel\UpdateHotelDTO;
use App\Modules\Hotel\Application\Mappers\HotelEntityMapper;
use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use App\Modules\Hotel\Domain\Entities\Hotel;
use DomainException;
use Illuminate\Support\Facades\Log;

class HotelService
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository
    ) {}

    public function create(CreateHotelDTO $dto): Hotel
    {
        $serviceId = uniqid('svc_', true);

        Log::info('Iniciando creación de hotel', [
            'service_id' => $serviceId,
            'function' => 'create',
            'dto_name' => $dto->name,
            'dto_nit' => $dto->nit
        ]);

        // Validar unicidad de nombre
        if ($this->hotelRepository->findByName($dto->name)) {
            Log::warning('Validación de nombre único falló', [
                'service_id' => $serviceId,
                'function' => 'create',
                'name' => $dto->name
            ]);
            throw new DomainException('El nombre del hotel ya existe.');
        }

        Log::info('Validación de nombre único pasada', [
            'service_id' => $serviceId,
            'function' => 'create',
            'name' => $dto->name
        ]);

        // Validar unicidad de NIT
        if ($this->hotelRepository->findByNit($dto->nit)) {
            Log::warning('Validación de NIT único falló', [
                'service_id' => $serviceId,
                'function' => 'create',
                'nit' => $dto->nit
            ]);
            throw new DomainException('El NIT del hotel ya existe.');
        }

        Log::info('Validación de NIT único pasada', [
            'service_id' => $serviceId,
            'function' => 'create',
            'nit' => $dto->nit
        ]);

        // Crear la entidad de dominio usando el mapper
        $hotel = HotelEntityMapper::toEntityFromCreateDTO($dto);

        Log::info('Entidad Hotel creada', [
            'service_id' => $serviceId,
            'function' => 'create',
            'hotel_id' => $hotel->getId(),
            'hotel_name' => $hotel->getName()
        ]);

        // Guardar a través del repositorio
        $this->hotelRepository->save($hotel);

        Log::info('Hotel guardado exitosamente', [
            'service_id' => $serviceId,
            'function' => 'create',
            'hotel_id' => $hotel->getId()
        ]);

        return $hotel;
    }

    public function update(UpdateHotelDTO $dto): Hotel
    {
        $serviceId = uniqid('svc_', true);

        Log::info('Iniciando actualización de hotel', [
            'service_id' => $serviceId,
            'function' => 'update',
            'hotel_id' => $dto->id
        ]);

        // Obtener el hotel existente
        $hotel = $this->hotelRepository->findById($dto->id);
        if (!$hotel) {
            Log::error('Hotel no encontrado para actualización', [
                'service_id' => $serviceId,
                'function' => 'update',
                'hotel_id' => $dto->id
            ]);
            throw new DomainException('Hotel no encontrado.');
        }

        Log::info('Hotel existente recuperado', [
            'service_id' => $serviceId,
            'function' => 'update',
            'hotel_id' => $hotel->getId(),
            'current_name' => $hotel->getName()
        ]);

        // Validar nombre único (excluyendo el propio hotel)
        $existingByName = $this->hotelRepository->findByName($dto->name);
        if ($existingByName && $existingByName->getId() !== $hotel->getId()) {
            Log::warning('Validación de nombre único falló (otro hotel tiene el nombre)', [
                'service_id' => $serviceId,
                'function' => 'update',
                'name' => $dto->name,
                'conflicting_hotel_id' => $existingByName->getId()
            ]);
            throw new DomainException('El nombre del hotel ya está en uso por otro hotel.');
        }

        Log::info('Validación de nombre único pasada', [
            'service_id' => $serviceId,
            'function' => 'update',
            'name' => $dto->name
        ]);

        // Validar NIT único (excluyendo el propio hotel)
        $existingByNit = $this->hotelRepository->findByNit($dto->nit);
        if ($existingByNit && $existingByNit->getId() !== $hotel->getId()) {
            Log::warning('Validación de NIT único falló (otro hotel tiene el NIT)', [
                'service_id' => $serviceId,
                'function' => 'update',
                'nit' => $dto->nit,
                'conflicting_hotel_id' => $existingByNit->getId()
            ]);
            throw new DomainException('El NIT del hotel ya está en uso por otro hotel.');
        }

        Log::info('Validación de NIT único pasada', [
            'service_id' => $serviceId,
            'function' => 'update',
            'nit' => $dto->nit
        ]);

        // Actualizar propiedades de la entidad
        $hotel->setName($dto->name);
        $hotel->setAddress($dto->address);
        $hotel->setNit($dto->nit);
        $hotel->setMaxRooms($dto->maxRooms);

        Log::info('Entidad Hotel actualizada en memoria', [
            'service_id' => $serviceId,
            'function' => 'update',
            'hotel_id' => $hotel->getId(),
            'new_name' => $hotel->getName(),
            'new_max_rooms' => $hotel->getMaxRooms()
        ]);

        // Guardar cambios
        $this->hotelRepository->save($hotel);

        Log::info('Hotel actualizado y guardado exitosamente', [
            'service_id' => $serviceId,
            'function' => 'update',
            'hotel_id' => $hotel->getId()
        ]);

        return $hotel;
    }
}
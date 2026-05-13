<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Application\Services;

use App\Modules\Hotel\Application\DTOs\HotelConfiguration\CreateHotelConfigurationDTO;
use App\Modules\Hotel\Application\DTOs\HotelConfiguration\UpdateHotelConfigurationDTO;
use App\Modules\Hotel\Application\Mappers\HotelConfigurationEntityMapper;
use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use App\Modules\Hotel\Domain\Repositories\HotelConfigurationRepositoryInterface;
use App\Modules\Hotel\Domain\Entities\HotelConfiguration;
use DomainException;
use Illuminate\Support\Facades\Log;

class HotelConfigurationService
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository,
        private readonly HotelConfigurationRepositoryInterface $configurationRepository
    ) {}

    public function create(CreateHotelConfigurationDTO $dto): HotelConfiguration
    {
        $serviceId = uniqid('svc_', true);

        Log::info('Iniciando creación de configuración de hotel', [
            'service_id' => $serviceId,
            'function' => 'create',
            'hotel_id' => $dto->hotelId,
            'room_type_id' => $dto->roomTypeId,
            'accommodation_id' => $dto->accommodationId,
            'quantity' => $dto->quantity
        ]);

        // Verificar que el hotel existe
        $hotel = $this->findHotel($dto->hotelId, $serviceId);

        // Validaciones de negocio
        $this->validateConfiguration($dto->hotelId, $dto->roomTypeId, $dto->accommodationId, $dto->quantity, null, $serviceId);

        // Crear la entidad de dominio usando el mapper (genera nuevo UUID)
        $configuration = HotelConfigurationEntityMapper::toEntityFromCreateDTO($dto);

        Log::info('Entidad HotelConfiguration creada', [
            'service_id' => $serviceId,
            'function' => 'create',
            'configuration_id' => $configuration->getId(),
            'quantity' => $configuration->getQuantity()
        ]);

        // Guardar a través del repositorio
        $this->configurationRepository->save($configuration);

        Log::info('Configuración guardada exitosamente', [
            'service_id' => $serviceId,
            'function' => 'create',
            'configuration_id' => $configuration->getId()
        ]);

        return $configuration;
    }

    public function update(UpdateHotelConfigurationDTO $dto): HotelConfiguration
    {
        $serviceId = uniqid('svc_', true);

        Log::info('Iniciando actualización de configuración de hotel', [
            'service_id' => $serviceId,
            'function' => 'update',
            'configuration_id' => $dto->id
        ]);

        // Obtener la configuración existente
        $configuration = $this->configurationRepository->findById($dto->id);
        if (!$configuration) {
            Log::error('Configuración no encontrada', [
                'service_id' => $serviceId,
                'function' => 'update',
                'configuration_id' => $dto->id
            ]);
            throw new DomainException('Hotel configuration not found.');
        }

        Log::info('Configuración existente recuperada', [
            'service_id' => $serviceId,
            'function' => 'update',
            'configuration_id' => $configuration->getId(),
            'current_quantity' => $configuration->getQuantity()
        ]);

        // Verificar que el hotel existe
        $hotel = $this->findHotel($dto->hotelId, $serviceId);

        // Validaciones de negocio (excluyendo la propia configuración)
        $this->validateConfiguration(
            $dto->hotelId,
            $dto->roomTypeId,
            $dto->accommodationId,
            $dto->quantity,
            $dto->id,
            $serviceId
        );

        // Actualizar la entidad con los nuevos valores
        $configuration->setQuantity($dto->quantity);
        // Nota: Si se permite cambiar hotelId, roomTypeId o accommodationId, habría que añadir setters en la entidad.
        // Por ahora asumimos que solo la cantidad es modificable. Si necesitas más campos, agrégalos.

        Log::info('Entidad HotelConfiguration actualizada en memoria', [
            'service_id' => $serviceId,
            'function' => 'update',
            'configuration_id' => $configuration->getId(),
            'new_quantity' => $configuration->getQuantity()
        ]);

        // Guardar cambios
        $this->configurationRepository->save($configuration);

        Log::info('Configuración actualizada y guardada exitosamente', [
            'service_id' => $serviceId,
            'function' => 'update',
            'configuration_id' => $configuration->getId()
        ]);

        return $configuration;
    }

    /**
     * Validaciones centralizadas que reemplazan los validadores externos.
     */
    private function validateConfiguration(
        string $hotelId,
        string $roomTypeId,
        string $accommodationId,
        int $quantity,
        ?string $ignoreConfigurationId,
        string $serviceId
    ): void {
        // 1. Validar que la combinación (hotel_id, room_type_id, accommodation_id) sea única
        $exists = $this->configurationRepository->existsCombination(
            $hotelId,
            $roomTypeId,
            $accommodationId
        );


        if ($exists) {
            Log::warning('Combinación duplicada de configuración', [
                'service_id' => $serviceId,
                'hotel_id' => $hotelId,
                'room_type_id' => $roomTypeId,
                'accommodation_id' => $accommodationId,
                'ignore_id' => $ignoreConfigurationId
            ]);
            throw new DomainException('Ya existe una configuración para ese hotel, tipo de habitación y acomodación.');
        }

        // 2. Validar que la cantidad no supere el máximo de habitaciones del hotel (max_rooms)
        $hotel = $this->findHotel($hotelId, $serviceId);
        $currentTotal = $this->configurationRepository->sumQuantityByHotel($hotelId, $ignoreConfigurationId);
        if (($currentTotal + $quantity) > $hotel->getMaxRooms()) {
            Log::warning('La cantidad total excede el máximo de habitaciones del hotel', [
                'service_id' => $serviceId,
                'hotel_id' => $hotelId,
                'max_rooms' => $hotel->getMaxRooms(),
                'current_total' => $currentTotal,
                'new_quantity' => $quantity
            ]);
            throw new DomainException('La cantidad total de habitaciones configuradas supera el máximo permitido para el hotel.');
        }

        // 3. Validar que la acomodación sea válida para el tipo de habitación
        // Esta lógica depende de reglas de negocio (ej: una suite no puede tener acomodación triple).
        // Por simplicidad, asumimos que existe un servicio o repositorio de reglas.
        // Aquí llamaríamos a un validador interno o a un servicio de dominio.
        if (!$this->isValidAccommodationForRoomType($roomTypeId, $accommodationId)) {
            Log::warning('Acomodación no válida para el tipo de habitación', [
                'service_id' => $serviceId,
                'room_type_id' => $roomTypeId,
                'accommodation_id' => $accommodationId
            ]);
            throw new DomainException('La acomodación no es válida para el tipo de habitación seleccionado.');
        }

        Log::info('Validaciones de configuración superadas', ['service_id' => $serviceId]);
    }

    private function findHotel(string $hotelId, string $serviceId): \App\Modules\Hotel\Domain\Entities\Hotel
    {
        $hotel = $this->hotelRepository->findById($hotelId);
        if (!$hotel) {
            Log::error('Hotel no encontrado', [
                'service_id' => $serviceId,
                'hotel_id' => $hotelId
            ]);
            throw new DomainException('Hotel not found.');
        }
        return $hotel;
    }

    /**
     * Ejemplo de validación de acomodación según el tipo de habitación.
     * Debes implementar esta lógica según tu negocio.
     */
    private function isValidAccommodationForRoomType(string $roomTypeId, string $accommodationId): bool
    {
        // Aquí llamarías a un repositorio o servicio de reglas.
        // Por ahora retornamos true para que no bloquee, pero debes reemplazar.
        return true;
    }
}

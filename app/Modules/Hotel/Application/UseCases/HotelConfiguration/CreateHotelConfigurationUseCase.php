<?php

namespace App\Modules\Hotel\Application\UseCases\HotelConfiguration;

use App\Modules\Hotel\Application\DTOs\HotelConfiguration\CreateHotelConfigurationDTO;
use App\Modules\Hotel\Application\Mappers\HotelConfigurationEntityMapper;
use App\Modules\Hotel\Domain\Repositories\HotelConfigurationRepositoryInterface;
use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateHotelConfigurationUseCase
{
    public function __construct(
        private readonly HotelConfigurationRepositoryInterface $configurationRepository,
        private readonly HotelRepositoryInterface $hotelRepository  // necesidad para validar hotel
    ) {}

    public function execute(CreateHotelConfigurationDTO $dto): \App\Modules\Hotel\Domain\Entities\HotelConfiguration
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Iniciando caso de uso de creación de configuración', [
            'use_case_id' => $useCaseId,
            'hotel_id'    => $dto->hotelId,
            'room_type_id' => $dto->roomTypeId,
            'accommodation_id' => $dto->accommodationId
        ]);

        try {
            $result = DB::transaction(function () use ($dto, $useCaseId) {
                // 1. Verificar que el hotel existe
                $hotel = $this->hotelRepository->findById($dto->hotelId);
                if (!$hotel) {
                    throw new DomainException("Hotel with ID {$dto->hotelId} not found.");
                }

                // 2. Validar combinación única (hotel, room_type, accommodation)
                $exists = $this->configurationRepository->existsCombination(
                    $dto->hotelId,
                    $dto->roomTypeId,
                    $dto->accommodationId
                );
                if ($exists) {
                    throw new DomainException('Ya existe una configuración para ese hotel, tipo de habitación y acomodación.');
                }

                // 3. Validar que la cantidad total no exceda max_rooms del hotel
                $currentTotal = $this->configurationRepository->sumQuantityByHotel($dto->hotelId);
                if (($currentTotal + $dto->quantity) > $hotel->getMaxRooms()) {
                    throw new DomainException('La cantidad total de habitaciones configuradas supera el máximo permitido para el hotel.');
                }

                // 4. Crear entidad mediante el mapper
                $configuration = HotelConfigurationEntityMapper::toEntityFromCreateDTO($dto);

                Log::info('Entidad creada, guardando en repositorio', [
                    'use_case_id' => $useCaseId,
                    'config_id'   => $configuration->getId()
                ]);

                // 5. Persistir
                $this->configurationRepository->save($configuration);

                return $configuration;
            });

            Log::info('Caso de uso completado', [
                'use_case_id' => $useCaseId,
                'configuration_id' => $result->getId()
            ]);

            return $result;
        } catch (Throwable $e) {
            Log::error('Error en caso de uso', [
                'use_case_id' => $useCaseId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
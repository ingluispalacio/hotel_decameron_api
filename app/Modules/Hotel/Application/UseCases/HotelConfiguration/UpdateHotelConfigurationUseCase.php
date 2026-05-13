<?php

namespace App\Modules\Hotel\Application\UseCases\HotelConfiguration;

use App\Modules\Hotel\Application\DTOs\HotelConfiguration\UpdateHotelConfigurationDTO;
use App\Modules\Hotel\Application\Mappers\HotelConfigurationEntityMapper;
use App\Modules\Hotel\Domain\Repositories\HotelConfigurationRepositoryInterface;
use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateHotelConfigurationUseCase
{
    public function __construct(
        private readonly HotelConfigurationRepositoryInterface $configurationRepository,
        private readonly HotelRepositoryInterface $hotelRepository
    ) {}

    public function execute(UpdateHotelConfigurationDTO $dto): \App\Modules\Hotel\Domain\Entities\HotelConfiguration
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Iniciando caso de uso de actualización de configuración', [
            'use_case_id' => $useCaseId,
            'configuration_id' => $dto->id
        ]);

        try {
            $result = DB::transaction(function () use ($dto, $useCaseId) {
                // 1. Verificar que la configuración existe (opcional, pero recomendado)
                $existing = $this->configurationRepository->findById($dto->id);
                if (!$existing) {
                    throw new DomainException("Hotel configuration with ID {$dto->id} not found.");
                }

                // 2. Verificar que el hotel existe
                $hotel = $this->hotelRepository->findById($dto->hotelId);
                if (!$hotel) {
                    throw new DomainException("Hotel with ID {$dto->hotelId} not found.");
                }

                // 3. Validar combinación única (excluyendo esta configuración)
                $exists = $this->configurationRepository->existsCombination(
                    $dto->hotelId,
                    $dto->roomTypeId,
                    $dto->accommodationId,
                    $dto->id  // parámetro ignoreId
                );
                if ($exists) {
                    throw new DomainException('Ya existe una configuración para ese hotel, tipo de habitación y acomodación.');
                }

                // 4. Validar límite de habitaciones (excluyendo esta configuración)
                $currentTotal = $this->configurationRepository->sumQuantityByHotel($dto->hotelId, $dto->id);
                if (($currentTotal + $dto->quantity) > $hotel->getMaxRooms()) {
                    throw new DomainException('La cantidad total de habitaciones configuradas supera el máximo permitido para el hotel.');
                }

                // 5. Crear nueva entidad con los datos actualizados (usando mapper)
                $configuration = HotelConfigurationEntityMapper::toEntityFromUpdateDTO($dto);

                // 6. Persistir
                $this->configurationRepository->save($configuration);

                Log::info('Configuración actualizada exitosamente', [
                    'use_case_id' => $useCaseId,
                    'configuration_id' => $configuration->getId()
                ]);

                return $configuration;
            });

            Log::info('Caso de uso de actualización completado', [
                'use_case_id' => $useCaseId,
                'result_configuration_id' => $result->getId()
            ]);

            return $result;
        } catch (Throwable $e) {
            Log::error('Error en caso de uso de actualización', [
                'use_case_id' => $useCaseId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
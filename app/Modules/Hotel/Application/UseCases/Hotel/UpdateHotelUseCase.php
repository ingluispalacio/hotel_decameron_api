<?php

namespace App\Modules\Hotel\Application\UseCases\Hotel;

use App\Modules\Hotel\Application\DTOs\Hotel\UpdateHotelDTO;
use App\Modules\Hotel\Application\Mappers\HotelEntityMapper;
use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use App\Modules\Hotel\Domain\Entities\Hotel;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateHotelUseCase
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository
    ) {}

    public function execute(UpdateHotelDTO $dto): Hotel
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Iniciando caso de uso de actualización de hotel', [
            'use_case_id' => $useCaseId,
            'hotel_id'    => $dto->id
        ]);

        try {
            $result = DB::transaction(function () use ($dto, $useCaseId) {
                // Verificar que el hotel existe (opcional, pero recomendado)
                $existingHotel = $this->hotelRepository->findById($dto->id);
                if (!$existingHotel) {
                    throw new DomainException("Hotel with ID {$dto->id} not found.");
                }

                // Validar unicidad de nombre y NIT (excluyendo el propio hotel)
                $existingByName = $this->hotelRepository->findByName($dto->name);
                if ($existingByName && $existingByName->getId() !== $dto->id) {
                    throw new DomainException("The hotel name '{$dto->name}' is already in use by another hotel.");
                }

                $existingByNit = $this->hotelRepository->findByNit($dto->nit);
                if ($existingByNit && $existingByNit->getId() !== $dto->id) {
                    throw new DomainException("The NIT '{$dto->nit}' is already in use by another hotel.");
                }

                // Crear una nueva entidad usando el mapper (con el ID del DTO)
                $hotel = HotelEntityMapper::toEntityFromUpdateDTO($dto);

                // Si usas soft delete, la entidad creada tendrá deletedAt = null.
                // Si el hotel original estaba eliminado suavemente y no quieres restaurarlo,
                // deberías copiar el estado de borrado. Por simplicidad, se omite.

                // Guardar (el repositorio decidirá si es insert o update según la existencia del ID)
                $this->hotelRepository->save($hotel);

                Log::info('Hotel actualizado exitosamente', [
                    'use_case_id' => $useCaseId,
                    'hotel_id'    => $hotel->getId()
                ]);

                return $hotel;
            });

            Log::info('Caso de uso de actualización completado', [
                'use_case_id' => $useCaseId,
                'result_hotel_id' => $result->getId()
            ]);

            return $result;
        } catch (Throwable $e) {
            Log::error('Error en caso de uso de actualización', [
                'use_case_id' => $useCaseId,
                'error'       => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
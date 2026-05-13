<?php

namespace App\Modules\Hotel\Application\UseCases\Hotel;

use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\Log;
use Throwable;

class DeleteHotelUseCase
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository
    ) {}

    public function execute(string $id): void
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Iniciando eliminación de hotel', [
            'use_case_id' => $useCaseId,
            'hotel_id' => $id
        ]);

        try {
            // Obtener la entidad de dominio
            $hotel = $this->hotelRepository->findById($id);
            if (!$hotel) {
                throw new DomainException("Hotel with ID {$id} not found.");
            }

            // Eliminar la entidad a través del repositorio
            $this->hotelRepository->delete($hotel);

            Log::info('Hotel eliminado exitosamente', [
                'use_case_id' => $useCaseId,
                'hotel_id' => $id
            ]);
        } catch (Throwable $e) {
            Log::error('Fallo en eliminación de hotel', [
                'use_case_id' => $useCaseId,
                'hotel_id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
<?php

namespace App\Modules\Hotel\Application\UseCases\Hotel;

use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use App\Modules\Hotel\Domain\Entities\Hotel;
use Illuminate\Support\Facades\Log;
use Throwable;

class GetHotelByIdUseCase
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository
    ) {}

    public function execute(string $id): ?Hotel
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Buscando hotel por ID', [
            'use_case_id' => $useCaseId,
            'hotel_id' => $id
        ]);

        try {
            $hotel = $this->hotelRepository->findById($id);

            if ($hotel === null) {
                Log::info('Hotel no encontrado', [
                    'use_case_id' => $useCaseId,
                    'hotel_id' => $id
                ]);
                throw new \DomainException("Hotel with ID {$id} not found.");
            } else {
                Log::info('Hotel encontrado', [
                    'use_case_id' => $useCaseId,
                    'hotel_id' => $hotel->getId(),
                    'hotel_name' => $hotel->getName()
                ]);
            }

            return $hotel;
        } catch (Throwable $e) {
            Log::error('Error al buscar hotel por ID', [
                'use_case_id' => $useCaseId,
                'hotel_id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
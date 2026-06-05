<?php

namespace App\Modules\Hotel\Application\UseCases\Hotel;

use App\Modules\Hotel\Application\DTOs\Hotel\HotelResponseDTO;
use App\Modules\Hotel\Domain\Repositories\CityRepositoryInterface;
use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Throwable;

class GetHotelByIdUseCase
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository,
        private readonly CityRepositoryInterface $cityRepository
    ) {}

    public function execute(string $id): HotelResponseDTO
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
            }

            Log::info('Hotel encontrado', [
                'use_case_id' => $useCaseId,
                'hotel_id' => $hotel->getId(),
                'hotel_name' => $hotel->getName()
            ]);

            return $this->mapHotelToResponse($hotel);
        } catch (Throwable $e) {
            Log::error('Error al buscar hotel por ID', [
                'use_case_id' => $useCaseId,
                'hotel_id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function mapHotelToResponse($hotel): HotelResponseDTO
    {
        $city = $this->cityRepository->findById($hotel->getCityId());

        return new HotelResponseDTO(
            $hotel->getId(),
            $hotel->getName(),
            $hotel->getAddress(),
            $city?->getName() ?? '',
            $hotel->getNit(),
            $hotel->getMaxRooms()
        );
    }
}
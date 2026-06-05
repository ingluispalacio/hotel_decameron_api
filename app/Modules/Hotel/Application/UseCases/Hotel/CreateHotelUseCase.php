<?php

namespace App\Modules\Hotel\Application\UseCases\Hotel;

use App\Modules\Hotel\Application\DTOs\Hotel\CreateHotelDTO;
use App\Modules\Hotel\Application\DTOs\Hotel\HotelResponseDTO;
use App\Modules\Hotel\Application\Services\HotelService;
use App\Modules\Hotel\Domain\Repositories\CityRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CreateHotelUseCase
{
    public function __construct(
        private readonly HotelService $hotelService,
        private readonly CityRepositoryInterface $cityRepository
    ) {}

    public function execute(CreateHotelDTO $dto): HotelResponseDTO
    {
        $hotel = DB::transaction(function () use ($dto) {
            return $this->hotelService->create($dto);
        });

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
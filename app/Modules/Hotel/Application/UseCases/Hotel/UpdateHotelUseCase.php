<?php

namespace App\Modules\Hotel\Application\UseCases\Hotel;

use App\Modules\Hotel\Application\DTOs\Hotel\HotelResponseDTO;
use App\Modules\Hotel\Application\DTOs\Hotel\UpdateHotelDTO;
use App\Modules\Hotel\Application\Services\HotelService;
use App\Modules\Hotel\Domain\Repositories\CityRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateHotelUseCase
{
    public function __construct(
        private readonly HotelService $hotelService,
        private readonly CityRepositoryInterface $cityRepository
    ) {}

    public function execute(UpdateHotelDTO $dto): HotelResponseDTO
    {
        $hotel = DB::transaction(fn() => $this->hotelService->update($dto));

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
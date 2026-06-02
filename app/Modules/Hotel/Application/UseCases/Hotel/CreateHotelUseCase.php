<?php

namespace App\Modules\Hotel\Application\UseCases\Hotel;

use App\Modules\Hotel\Application\DTOs\Hotel\CreateHotelDTO;
use App\Modules\Hotel\Application\Services\HotelService;
use App\Modules\Hotel\Domain\Entities\Hotel;
use Illuminate\Support\Facades\DB;

class CreateHotelUseCase
{
    public function __construct(
        private readonly HotelService $hotelService
    ) {}

    public function execute(CreateHotelDTO $dto): Hotel
    {
        return DB::transaction(function () use ($dto) {
            return $this->hotelService->create($dto);
        });
    }
}
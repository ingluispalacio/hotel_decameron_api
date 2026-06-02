<?php

namespace App\Modules\Hotel\Application\UseCases\Hotel;

use App\Modules\Hotel\Application\DTOs\Hotel\UpdateHotelDTO;
use App\Modules\Hotel\Application\Services\HotelService;
use App\Modules\Hotel\Domain\Entities\Hotel;
use DomainException;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateHotelUseCase
{
    public function __construct(private readonly HotelService $hotelService) {}

    public function execute(UpdateHotelDTO $dto): Hotel
    {
        return DB::transaction(fn() => $this->hotelService->update($dto));
    }
}
<?php

namespace App\Modules\Hotel\Application\UseCases\HotelConfiguration;

use App\Modules\Hotel\Application\DTOs\HotelConfiguration\UpdateHotelConfigurationDTO;
use App\Modules\Hotel\Application\Services\HotelConfigurationService;
use App\Modules\Hotel\Domain\Entities\HotelConfiguration;
use Illuminate\Support\Facades\DB;

class UpdateHotelConfigurationUseCase
{
    public function __construct(
        private readonly HotelConfigurationService $service
    ) {}

    public function execute(UpdateHotelConfigurationDTO $dto): HotelConfiguration
    {
        return DB::transaction(function () use ($dto) {
            return $this->service->update($dto);
        });
    }
}
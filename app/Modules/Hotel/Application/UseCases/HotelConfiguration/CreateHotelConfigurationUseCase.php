<?php

namespace App\Modules\Hotel\Application\UseCases\HotelConfiguration;

use App\Modules\Hotel\Application\DTOs\HotelConfiguration\CreateHotelConfigurationDTO;
use App\Modules\Hotel\Application\Services\HotelConfigurationService;
use App\Modules\Hotel\Domain\Entities\HotelConfiguration;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateHotelConfigurationUseCase
{
    public function __construct(
        private readonly HotelConfigurationService $service
    ) {}

    public function execute(CreateHotelConfigurationDTO $dto): HotelConfiguration
    {
        return DB::transaction(function () use ($dto) {
            return $this->service->create($dto);
        });
    }
}